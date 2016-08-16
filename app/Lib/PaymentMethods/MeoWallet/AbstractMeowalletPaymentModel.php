<?php

namespace App\Lib\PaymentMethods\MeoWallet;

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

    protected function processPayment($transaction_id, $invoice_id, $status, $amount, $method)
    {
        Log::info(sprintf("Processing payment for invoice_id '%s' with status '%s', amount '%s'", $invoice_id, $status, $amount));
        // TODO CHANGE ALL
        return false;

        $order = $this->_getSalesOrderModel()->loadByIncrementId($invoice_id);

        if (null == $order)
        {
            throw new \InvalidArgumentException("Unknown order with invoice_id '$invoice_id'");
        }

        $payment  = $order->getPayment();

        if (null == $payment)
        {
            throw new Exception('No payment associated with an order?!');
        }

        $comment = Mage::helper('meowallet')->__('%s status update: %s<br/>Payment Method: %s', "MEO Wallet", $status, $method);
        $order->addStatusHistoryComment($comment);

        switch ($status)
        {
            case 'COMPLETED':
                $action = $this->_getPaymentConfig('payment_action');
                $this->_registerPayment($transaction_id, $payment, $amount, $action);
                $order->sendOrderUpdateEmail();
                break;

            case 'FAIL':
                $order->cancel();
                $order->sendOrderUpdateEmail();
                break;

            case 'CREATED':
            case 'PENDING':
                break;

            default:
                throw new \InvalidArgumentException("Payment operation status '$status' not handled by this module!");
        }
        $order->save();
    }

    public function processCallback($verbatim_callback)
    {
        if (false === $this->_isValidCallback($verbatim_callback))
        {
            throw new \InvalidArgumentException('Invalid callback');
        }

        $callback = json_decode($verbatim_callback);

        Log::info(sprintf("MEOWallet callback for invoice_id '%s' with status '%s'", $callback->ext_invoiceid, $callback->operation_status));

        $this->processPayment($callback->operation_id, $callback->ext_invoiceid, $callback->operation_status, $callback->amount, $callback->method);
    }
}
