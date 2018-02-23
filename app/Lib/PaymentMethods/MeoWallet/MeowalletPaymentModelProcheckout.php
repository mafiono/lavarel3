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
        $payment = [
            'client' => $order['client'],
            'amount' => $order['amount'],
            'currency' => $order['currency'],
            'items' => $order['items'],
            'ext_invoiceid' => $order['trans_id'],
            'ext_costumerid' => $order['user_id'],
            'ext_email' => $order['client']['email'],
        ];

        $request_data = [
            'payment' => $payment,
            'required_fields' => [
//                'name' => true,
//                'email' => true,
//                'nif' => true,
            ],
            'exclude' => $exclude,
            'default_method' => $default_method,
            'url_confirm' => $url_confirm,
            'url_cancel' => $url_cancel
        ];

        $response = $this->http('checkout', $request_data);
        if (false == is_object($response)
            || false == property_exists($response, 'id')
            || false == property_exists($response, 'url_redirect')
        ) {
            $response_data = var_export($response);
            throw new Exception('Could not create MEO Wallet procheckout: ' . $response_data);
//            throw new Exception(sprintf('%s. Service response: %s %s: %s',
//                'Could not create MEO Wallet procheckout',
//                $response_data, curl_errno($ch), curl_error($ch)));
        }
        $trans->api_transaction_id = $response->id;
        $trans->save();

        return $response;
    }

    /**
     * @param UserTransaction $trans
     * @param $order
     * @return mixed
     */
    public function createMb(UserTransaction $trans, $order)
    {
        $response = $this->http('mb/pay', $order);

        if (false == is_object($response)
            || false == property_exists($response, 'id')
            || false == property_exists($response, 'status')
        ) {
            $response_data = var_export($response);
            throw new Exception('Could not create MEO Wallet procheckout: ' . $response_data);
//            throw new Exception(sprintf('%s. Service response: %s %s: %s',
//                'Could not create MEO Wallet procheckout',
//                $response_data, curl_errno($ch), curl_error($ch)));
        }
        $trans->api_transaction_id = $response->id;
        $trans->save();

        return $response;
    }

    public function createUniqueMbRef($order)
    {
        $response = $this->http('mb/pay', $order);

        if (false == is_object($response)
            || false == property_exists($response, 'id')
            || false == property_exists($response, 'status')
        ) {
            $response_data = var_export($response);
            throw new Exception('Could not create MEO Wallet procheckout: ' . $response_data);
//            throw new Exception(sprintf('%s. Service response: %s %s: %s',
//                'Could not create MEO Wallet procheckout',
//                $response_data, curl_errno($ch), curl_error($ch)));
        }

        return $response;
    }
}
