<?php

namespace App\Netent;

use App\Models\CasinoGame;
use App\Models\CasinoSession;
use SoapClient;

class Netent extends SoapClient
{
    protected $auth;

    public function __construct()
    {
        parent::__construct(config('app.netent_wsdl'));

        $this->auth = [
            'merchantId' => config('app.netent_merchant_id'),
            'merchantPassword' => config('app.netent_merchant_password'),
        ];
    }

    public function loginUserDetailed($userId, $extra = [])
    {
        return $this->__soapCall('loginUserDetailed', [
            'parameters' => $this->auth + [
                'userName' => $userId,
                'currencyISOCode' => 'EUR',
                'extra' => $extra
            ]
        ])->loginUserDetailedReturn;
    }

    public function logoutUser($userId)
    {
        CasinoSession::whereUserId($userId)
            ->whereProvider('netent')
            ->whereSessionstatus('active')
            ->get()
            ->each(function($session) {
                $this->__soapCall('logoutUser', [
                    'parameters' => $this->auth + ['sessionId' => $session->sessionid]
                ]);
            });
    }
}