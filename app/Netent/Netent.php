<?php

namespace App\Netent;

use SoapClient;

class Netent extends SoapClient
{
    public function __construct()
    {
        parent::__construct(config('app.netent_wsdl'));
    }

    public function loginUserDetailed($userId)
    {
        return $this->__soapCall('loginUserDetailed', [
            'parameters' => [
                'userName' => $userId,
                'merchantId' => config('app.netent_merchant_id'),
                'merchantPassword' => config('app.netent_merchant_password'),
            ]
        ])->loginUserDetailedReturn;
    }
}