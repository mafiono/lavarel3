<?php

namespace App\Lib\PaymentMethods\PaySafeCard;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use SebastianWalker\Paysafecard\Amount;
use SebastianWalker\Paysafecard\Client;
use SebastianWalker\Paysafecard\Urls;

class PaySafeCardApi
{
    public $environment;
    protected $logger;
    protected $api;
    protected $privateKey;

    public function __construct($configs)
    {
        $test = $configs['settings']['mode'] !== 'live';
        $this->environment = $configs['settings']['mode'] === 'live';
        $this->privateKey = $configs[$configs['settings']['mode'] . '_privateKey'];

        $this->logger = new Logger('paysafecard');
        $this->logger->pushHandler(new StreamHandler($configs['settings']['log.FileName'], Logger::DEBUG));

        $this->api = new Client($this->privateKey);
        $this->api->setTestingMode($test);
    }

    public function createCharge($amount, $userId, $metadata, $successUrl, $failureUrl, $notificationUrl)
    {
        $urls = new Urls();
        $urls->setSuccessUrl($successUrl);
        $urls->setFailureUrl($failureUrl);
        $urls->setNotificationUrl($notificationUrl);
        $this->api->setUrls($urls);
        $amount = new Amount($amount);
        $amount->setCurrency('EUR');
        $pay = new MyPayment($amount);
        $pay->createWithCustomer($this->api, [
            'id' => $userId,
            'min_age' => 18,
            'kyc_level' => 'FULL',
            'metadata' => $metadata
        ]);

        return $pay;
    }

    public function processCharge($id)
    {
        try {
            $pay = MyPayment::myFind($id, $this->api);

            if ($pay->isAuthorized()) {
                $pay->myCapture($this->api);

                if ($pay->isSuccessful()) {
                    // Process

                } else {
                    throw new \Exception("Payment Failed (".$pay->getStatus().")");
                }
            } else if($pay->isFailed()){
                echo "Payment Failed (".$pay->getStatus().")";
            } else{
                echo "Other Status (".$pay->getStatus().")";
            }
        }catch (\Exception $e) {
            print_r($e->getMessage());
            print_r($e->getTraceAsString());
        }
    }

}