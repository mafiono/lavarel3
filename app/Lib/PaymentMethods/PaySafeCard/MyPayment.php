<?php

namespace App\Lib\PaymentMethods\PaySafeCard;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use SebastianWalker\Paysafecard\Amount;
use SebastianWalker\Paysafecard\Client;
use SebastianWalker\Paysafecard\Payment;

class MyPayment extends Payment
{
    protected $result;
    public static $logger;

    public function createWithCustomer(Client $client, array $customer)
    {
        $result = $client->sendRequest("post", "payments", [
            'type' => 'PAYSAFECARD',
            'amount' => $this->getAmount()->getAmount(),
            'currency' => $this->getAmount()->getCurrency(),
            'redirect' => [
                'success_url' => $client->getUrls()->getSuccessUrl(),
                'failure_url' => $client->getUrls()->getFailureUrl()
            ],
            'notification_url' => $client->getUrls()->getNotificationUrl(),
            'customer' => $customer
        ]);
        static::$logger->info('createWithCustomer', [$client, $result]);

        $this->fill($result);
        return $this;
    }

    public static function myFind($id, Client $client)
    {
        $result = $client->sendRequest("get","payments/".$id);
        $payment = new MyPayment();
        $payment->fill($result);
        $payment->result = $result;
//        print_r('My Find');
//        print_r($result);
        static::$logger->info('Result ' . $id, compact('result'));
        return $payment;
    }

    public function myCapture(Client $client){
        if($this->isAuthorized()){
            $result = $client->sendRequest("post","payments/".$this->getId()."/capture");
//            print_r('My Capture');
//            print_r($result);
            $this->fill($result);
        }
        return $this;
    }

    public function getDetails() {
        return json_encode($this->result);
    }

    public function getCustomerInfo()
    {
        return $this->result->customer;
    }
}