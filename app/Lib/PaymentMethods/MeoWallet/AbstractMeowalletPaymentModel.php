<?php

namespace App\Lib\PaymentMethods\MeoWallet;

use App\Models\TransactionTax;
use App\Models\UserDepositReference;
use App\Transaction;
use App\User;
use App\UserTransaction;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Log;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class AbstractMeowalletPaymentModel
{
    protected $_code = 'meowallet_abstract';
    protected $force = false;
    protected $applyCost = false;

	const SANDBOX_ENVIRONMENT_ID       = 'sandbox';
	const SANDBOX_SERVICE_ENDPOINT 	   = 'https://services.sandbox.meowallet.pt/api/v2';
	const PRODUCTION_ENVIRONMENT_ID    = 'production';
    const PRODUCTION_SERVICE_ENDPOINT  = 'https://services.wallet.pt/api/v2';

    protected $_configs;
    protected $logger;

    public function __construct($configs)
    {
        $this->_configs = $configs;
        $this->logger = new Logger('meo-wallet');
        $this->logger->pushHandler(new StreamHandler(storage_path('logs/meowallet.log'), Logger::DEBUG));
    }

    private function _getPaymentConfig()
    {
        return $this->_configs['settings']['mode'];
    }

    public function isSandBoxMode()
    {
        return $this->_getPaymentConfig() !== static::PRODUCTION_ENVIRONMENT_ID;
    }

    private function _isValidCallback($data)
    {
        $authToken    = $this->getAPIToken();
        $headers      = array(
                          'Authorization: WalletPT ' . $authToken,
                          'Content-Type: application/json',
                          'Content-Length: ' . strlen($data));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_URL, $this->getServiceEndpoint('callback/verify'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if (env('CURL_PROXY', false)) {
            curl_setopt($ch, CURLOPT_PROXY, env('CURL_PROXY'));
        }
        $response = curl_exec($ch);

        if (0 == strcasecmp('true', $response))
        {
            return true;
        }

	    if (0 != strcasecmp('false', $response))
    	{
	        $this->logger->alert("MEOWallet callback validation returned unexpected response '$response'");
    	}

        return false;
    }

	protected function getServiceEndpoint($path = null)
	{
        $environment = $this->_getPaymentConfig();
		$url	     = null;

        switch ($environment)
        {
            case static::SANDBOX_ENVIRONMENT_ID:
                $url = static::SANDBOX_SERVICE_ENDPOINT;
                break;
            case static::PRODUCTION_ENVIRONMENT_ID:
                $url = static::PRODUCTION_SERVICE_ENDPOINT;
            	break;
        }

		if ( empty($url) )
		{
	        throw new Exception("Empty service endpoint for environment '$environment'");
		}

		return sprintf('%s/%s', $url, $path);
	}
	
	protected function getAPIToken()
	{
		$environment = $this->_getPaymentConfig();

        switch ($environment)
        {
            case static::SANDBOX_ENVIRONMENT_ID:
                return $this->_configs['sandbox_api_token'];
            case static::PRODUCTION_ENVIRONMENT_ID:
                return $this->_configs['production_api_token'];
        }

        $msg = "Invalid environment code '$environment'";
        $this->logger->error($msg);
        throw new Exception($msg);
    }

    public function processPayment($transaction_id, $invoice_id, $status, $amount, $method, $details = null)
    {
        $this->logger->info(sprintf("Processing payment for invoice_id '%s' with status '%s', amount '%s', trans_id: '%s', method: '%s'", $invoice_id, $status,
            $amount, $transaction_id, $method));

        // Check type of invoice
        if ($uniqueRef = (strpos($invoice_id, '_') > 0)) {
            list($user_id, $version) = explode('_', $invoice_id);
            $userDep = UserDepositReference::query()
                ->where('user_id', '=', $user_id)
                ->where('version', '=', $version)
                ->first()
                ;
            if ($userDep === null) {
                throw new ModelNotFoundException("UserDepositReference: $invoice_id");
            }
            $taxes = TransactionTax::query()
                ->where('transaction_id', '=', 'mb')
                ->where('method', '=', 'deposit')
                ->first()
                ;
            if ($taxes === null) {
                throw new ModelNotFoundException('TransactionTax: mb -> deposit');
            }
            $trans = collect([]);
            $user = $userDep->user;
        } else {
            /** @var UserTransaction[] $trans */
            $trans = collect(UserTransaction::where('transaction_id', '=', $invoice_id)->get());
            if (count($trans) === 0)
                throw new ModelNotFoundException('Transaction');
            /** @var User $user */
            $fT = $trans->first();
            $user = $fT->user;
            $tax = $fT->tax;
            $amountDeposit = $fT->debit;
        }

        $operations = $this->http('operations/?ext_invoiceid=' . $invoice_id);
//        print_r('Found ' . $operations->total . PHP_EOL);
        foreach ($operations->elements as $op) {
            if (strtolower($op->method) === 'wallet') {
                $op->method = 'meo_wallet';
            }
            $tran = $trans->first(function ($key, $item) use ($op) {
                $match = $item->api_transaction_id === $op->id;
                if ($match) return true;
                if ($item->transaction_details === null) return false;
                $json = json_decode($item->transaction_details);
                if (isset($json->payment->id)) {
                    return $json->payment->id === $op->id;
                }
                return false;
            });
            $details = json_encode($op);
            if ($uniqueRef) {
                $tran = UserTransaction::query()
                    ->where('user_id', '=', $user_id)
                    ->where('api_transaction_id', '=', $op->id)
                    ->first();

                $amountDeposit = (float)$op->amount;
                $tax = 0;
                if ($amountDeposit < $taxes->free_above) {
                    $amountDeposit = round($amountDeposit / (1 + ($taxes->tax/100)), 2);
                    $tax = (float)$op->amount - $amountDeposit;
                }
            }
//            print_r('Found ' . $op->id . PHP_EOL);
            if ($tran === null) {
                $tran = $user->newDeposit($amountDeposit, strtolower($op->method), $tax, $op->id);
                if (!$uniqueRef) {
                    $tran->transaction_id = $invoice_id;
                    $descTrans = Transaction::findOrNew(strtolower($op->method));
                    $tran->description = 'Depósito ' . $descTrans->name . ' ' . $invoice_id;
                    $tran->save();
                }
            }
            if ($op->id !== $tran->api_transaction_id) {
                // we can update the api_transaction_id (this can be the operation_id
                $tran->api_transaction_id = $op->id;
                $tran->save();
            }
            if (strtolower($op->method) !== $tran->origin) {
                // we can update the method type
                $tran->origin = strtolower($op->method);
                $tran->save();
            }
            if ($this->applyCost && $op->amount !== ($tran->debit + $tran->tax)) {
//                print_r('Removing Cost ' . ($tran->debit + $tran->credit + $tran->tax) . ' != ' . $op->amount . PHP_EOL);
                $tran->debit = $op->amount - $tran->tax;
                $tran->save();
            }
            if ($uniqueRef) {
                $invoice_id = $tran->transaction_id;
            }
            switch ($op->status)
            {
                case 'COMPLETED':
//                    print_r('Processing ' . $op->id . PHP_EOL);
                    $result = $user->updateTransaction($invoice_id, $op->amount, 'processed', $tran->user_session_id,
                        $op->id, $details, $op->fee, $this->force);
                    $msg = sprintf("Processing payment for invoice_id: %s, id %s, result %s",
                        $invoice_id, $op->id, $result);
                    $this->logger->info($msg);
                    print_r($msg . PHP_EOL);
                    break;

                case 'FAIL':
                    // nothing to do here
                    break;

                case 'CREATED':
                case 'PENDING':
                    break;

                default:
                    throw new \InvalidArgumentException("Payment operation status '$status' not handled by this module!");
            }
        }
    }

    public function processCallback($verbatim_callback)
    {
        if (false === $this->_isValidCallback($verbatim_callback))
        {
            throw new \InvalidArgumentException('Invalid callback');
        }

        $callback = json_decode($verbatim_callback);

        $this->logger->info(sprintf("MEOWallet callback for invoice_id '%s' with status '%s'", $callback->ext_invoiceid, $callback->operation_status));

        $this->processPayment($callback->operation_id, $callback->ext_invoiceid, $callback->operation_status, $callback->amount, $callback->method, $verbatim_callback);
    }

    public function http($url, $data = null, $method = null){

        $authToken    = $this->getAPIToken();
        $headers      = [
            'Authorization: WalletPT ' . $authToken,
            'Content-Type: application/json'
        ];
        if ($method === null) {
            $method = $data === null ? 'GET' : 'POST';
        }
        $request_data = null;
        if ($data !== null) {
            $request_data = json_encode($data);
            $headers[] = 'Content-Length: ' . strlen($request_data);
        }
        $url = $this->getServiceEndpoint($url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($request_data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request_data);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if (env('CURL_PROXY', false)) {
            curl_setopt($ch, CURLOPT_PROXY, env('CURL_PROXY'));
        }
        $response = curl_exec($ch);

        $this->logger->debug('MEO Wallet Recheck info', [$url, $data, $response, json_decode($response)]);

        return json_decode($response);
    }

    public function setForce($force)
    {
        $this->force = $force;
    }

    public function applyCost($cost)
    {
        $this->applyCost = $cost;
    }
}
