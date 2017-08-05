<?php

namespace App\Lib\PaymentMethods\SwitchPayments;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class SwitchApi
{
    const SANDBOX = 'https://api-test.switchpayments.com/v2/';
    const LIVE = 'https://api.switchpayments.com/v2/';
    public $environment;
    public $publicKey;

    protected $logger;

    public function __construct($configs)
    {
        $this->environment = $configs['settings']['mode'] === 'live' ? self::LIVE : self::SANDBOX;
        $this->merchantId = $configs[$configs['settings']['mode'].'_merchantId'];
        $this->privateKey = $configs[$configs['settings']['mode'].'_privateKey'];
        $this->publicKey = $configs[$configs['settings']['mode'].'_publicKey'];

        $this->logger = new Logger('switch_payments');
        $this->logger->pushHandler(new StreamHandler($configs['settings']['log.FileName'], Logger::DEBUG));
    }

    public function handleEvent($eventId, $eventHandlers)
    {
        $this->onEvent($eventId, $eventHandlers);
    }

    public function onEvent($eventId, $eventHandlers)
    {
        $event = $this->http('GET', 'events/' . $eventId);
        $this->logger->info('Events', ['event' => $event]);
        if (array_key_exists($event['type'], $eventHandlers)) {
            $eventHandlers[$event['type']]($event);
        }
    }

    public function getCharge($chargeId)
    {
        return $this->http('GET', 'charges/' . $chargeId);
    }

    public function getInstrument($instrumentId)
    {
        return $this->http('GET', 'instruments/' . $instrumentId);
    }

    public function getPayment($paymentId)
    {
        return $this->http('GET', 'payments/' . $paymentId);
    }

    public function createCharge($amount, $currency, $metadata, $eventsUrl, $redirectUrl, $chargeType, $instrumentParams = null)
    {
        $postData = array('amount' => $amount,
            'currency' => $currency,
            'metadata' => $metadata,
            'events_url' => $eventsUrl,
            'redirect_url' => $redirectUrl,
            'charge_type' => $chargeType);

        if ($instrumentParams != null) {
            $postData['instrument_params'] = $instrumentParams;
        }

        return $this->http('POST', 'charges/', json_encode($postData));
    }

    public function confirmCharge($chargeId)
    {
        return $this->http('POST', 'charges/' . $chargeId . '/confirm/');
    }

    public function createPayment($instrumentId, $amount, $currency, $description = '')
    {
        $postData = '{"instrument":"' . $instrumentId . '","amount":"' . $amount . '","currency":"' . $currency . '","description":"' . $description . '"}';
        return $this->http('POST', 'payments/', $postData);
    }

    public function refundPayment($paymentId, $amount, $description = '')
    {
        $postData = array('payment_id' => $paymentId,
            'amount' => $amount,
            'description' => $description);
        return $this->http('POST', 'payments/' . $paymentId . '/refund/', json_encode($postData));
    }

    public function http($method, $resource, $postData = '{}')
    {
        $url = $this->environment . $resource;
        $this->logger->debug("Requesting $url");

        $process = curl_init($url);

        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_USERPWD, $this->merchantId . ':' . $this->privateKey);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);

        curl_setopt($process, CURLOPT_VERBOSE, true);
        if (!env('FORCE_PROTOCOL', false)) {
            curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);
        }
        if (env('CURL_PROXY', false)) {
            curl_setopt($process, CURLOPT_PROXY, env('CURL_PROXY', false));
        }

        if (strtoupper($method) === 'POST') {
            curl_setopt($process, CURLOPT_POST, TRUE);
            curl_setopt($process, CURLOPT_POSTFIELDS, $postData);
        }

        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);

        $verbose = fopen('php://temp', 'wb+');
        curl_setopt($process, CURLOPT_STDERR, $verbose);

        $return = curl_exec($process);

        if ($return === FALSE) {
            $this->logger->error("cUrl error", [
                'process' => curl_errno($process),
                'msg' => htmlspecialchars(curl_error($process))
            ]);
        }


        $this->logger->debug("return " . $return);
        $httpCode = curl_getinfo($process, CURLINFO_HTTP_CODE);
        curl_close($process);

        $this->logger->debug("httpCode " . $httpCode);

        if ($httpCode == 401 || $httpCode == 403) {
            throw new \Exception('Wrong MerchantId or Private Key');
        }

        if ($httpCode >= 300) {
            throw new \Exception($return);
        }

        rewind($verbose);
        $verboseLog = stream_get_contents($verbose);

        $this->logger->debug("Verbose information:\n" . htmlspecialchars($verboseLog). "\n");

        return json_decode($return, true);
    }
}