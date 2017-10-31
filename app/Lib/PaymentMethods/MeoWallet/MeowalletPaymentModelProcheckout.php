<?php

namespace App\Lib\PaymentMethods\MeoWallet;

use App\UserTransaction;
use Exception;
use URL;
use Log;

class MeowalletPaymentModelProcheckout extends AbstractMeowalletPaymentModel
{
    protected $_code                    = 'meowallet_procheckout';
    protected $_canUseCheckout          = true;
    protected $_isGateway               = true;
    protected $_canAuthorize            = true;
    protected $_canRefund 	            = true;
    protected $_canVoid 	            = true;
    protected $_canReviewPayment        = true;
    protected $_supportedCurrencyCodes  = array('EUR');

    public function __construct($configs)
    {
        parent::__construct($configs);
    }

    /**
     * Return Order place redirect url
     *
     * @return string
     */
    public function getOrderPlaceRedirectUrl()
    {
        return URL::route('banco/depositar/meowallet/success?_secure=true');
    }

    public function createCheckout(UserTransaction $trans, $order, $exclude, $default_method, $url_confirm, $url_cancel)
    {
        $client = array('name' => $order['name'],
            'email' => $order['email']);

        $payment = [
            //'client' => $client,
            'amount' => $order['amount'],
            'currency' => $order['currency'],
            'items' => $order['items'],
            'ext_invoiceid' => $order['trans_id'],
            'ext_costumerid' => $order['user_id'],
            'ext_email' => $order['email'],
        ];

        $request_data = json_encode(array('payment' => $payment,
            'required_fields' => [
//                'name' => true,
//                'email' => true,
//                'nif' => true,
            ],
            'exclude' => $exclude,
            'default_method' => $default_method,
            'url_confirm' => $url_confirm,
            'url_cancel' => $url_cancel));
        $authToken = $this->getAPIToken();
        $headers = array(
            'Authorization: WalletPT ' . $authToken,
            'Content-Type: application/json',
            'Content-Length: ' . strlen($request_data)
        );
        $this->logger->info("Request", [$request_data]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_URL, $this->getServiceEndpoint('checkout'));
        if (env('CURL_PROXY', false)) {
            curl_setopt($ch, CURLOPT_PROXY, env('CURL_PROXY'));
        }
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        // TODO remove this later
        if ($this->isSandBoxMode()) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        $response = json_decode(curl_exec($ch));
        if (false == is_object($response)
            || false == property_exists($response, 'id')
            || false == property_exists($response, 'url_redirect')
        ) {
            $response_data = var_export($response);
            throw new Exception(sprintf('%s. Service response: %s %s: %s',
                'Could not create MEO Wallet procheckout',
                $response_data, curl_errno($ch), curl_error($ch)));
        }
        $trans->api_transaction_id = $response->id;
        $trans->save();

        $this->logger->info("Response", [$response]);
        // SAVE INFO TO TRANS

        return $response;
    }

    public function createMb(UserTransaction $trans, $order)
    {
        $request_data = json_encode($order);
        $authToken = $this->getAPIToken();
        $headers = array(
            'Authorization: WalletPT ' . $authToken,
            'Content-Type: application/json',
            'Content-Length: ' . strlen($request_data)
        );
        $this->logger->info("Request", [$request_data]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_URL, $this->getServiceEndpoint('mb/pay'));
        if (env('CURL_PROXY', false)) {
            curl_setopt($ch, CURLOPT_PROXY, env('CURL_PROXY'));
        }
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        // TODO remove this later
        if ($this->isSandBoxMode()) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        $response = json_decode(curl_exec($ch));
        if (false == is_object($response)
            || false == property_exists($response, 'id')
            || false == property_exists($response, 'status')
        ) {
            $response_data = var_export($response);
            throw new Exception(sprintf('%s. Service response: %s %s: %s',
                'Could not create MEO Wallet procheckout',
                $response_data, curl_errno($ch), curl_error($ch)));
        }
        $trans->api_transaction_id = $response->id;
        $trans->save();

        $this->logger->info("Response", [$response]);
        // SAVE INFO TO TRANS

        return $response;
    }
}
