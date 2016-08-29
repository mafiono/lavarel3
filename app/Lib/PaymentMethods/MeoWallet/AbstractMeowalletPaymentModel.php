<?php

namespace App\Lib\PaymentMethods\MeoWallet;

use App\User;
use App\UserTransaction;
use Exception;
use Log;

class AbstractMeowalletPaymentModel
{
    protected $_code = 'meowallet_abstract';

	const SANDBOX_ENVIRONMENT_ID       = 'sandbox';
	const SANDBOX_SERVICE_ENDPOINT 	   = 'https://services.sandbox.meowallet.pt/api/v2';
	const PRODUCTION_ENVIRONMENT_ID    = 'production';
    const PRODUCTION_SERVICE_ENDPOINT  = 'https://services.wallet.pt/api/v2';

    protected $_configs;

    public function __construct($configs)
    {
        $this->_configs = $configs;
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
        $response = curl_exec($ch);

        if (0 == strcasecmp('true', $response))
        {
            return true;
        }

	    if (0 != strcasecmp('false', $response))
    	{
	        Log::alert("MEOWallet callback validation returned unexpected response '$response'");
    	}

        return false;
    }

    public function getCheckoutInfo($id){
        $authToken    = $this->getAPIToken();
        $headers      = array(
            'Authorization: WalletPT ' . $authToken,
            'Content-Type: application/json');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_URL, $this->getServiceEndpoint('checkout/'.$id));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);

        Log::info("MEOWallet Recheck info", [$response]);
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
        Log::error($msg);
        throw new Exception($msg);
    }

    protected function processPayment($transaction_id, $invoice_id, $status, $amount, $method, $details)
    {
        Log::info(sprintf("Processing payment for invoice_id '%s' with status '%s', amount '%s', trans_id: '%s', method: '%s'", $invoice_id, $status,
            $amount, $transaction_id, $method));
        // TODO CHANGE ALL
        $trans = UserTransaction::findByTransactionId($invoice_id);
        if ($trans === null || $trans->status_id !== 'canceled') {
            throw new \Exception("Payment is already processed!");
        }

        $this->getCheckoutInfo($transaction_id);

        /** @var User $user */
        $user = $trans->user;

        switch ($status)
        {
            case 'COMPLETED':
                $result = $user->updateTransaction($invoice_id, $amount, 'processed', $trans->user_session_id, $transaction_id, $details);
                Log::info(sprintf("Processing payment for invoice_id: %s, result %s", $invoice_id, $result));
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

    public function processCallback($verbatim_callback)
    {
        if (false === $this->_isValidCallback($verbatim_callback))
        {
            throw new \InvalidArgumentException('Invalid callback');
        }

        $callback = json_decode($verbatim_callback);

        Log::info(sprintf("MEOWallet callback for invoice_id '%s' with status '%s'", $callback->ext_invoiceid, $callback->operation_status));

        $this->processPayment($callback->operation_id, $callback->ext_invoiceid, $callback->operation_status, $callback->amount, $callback->method, $verbatim_callback);
    }
}
