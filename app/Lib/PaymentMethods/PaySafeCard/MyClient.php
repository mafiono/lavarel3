<?php

namespace App\Lib\PaymentMethods\PaySafeCard;

use Lang;
use SebastianWalker\Paysafecard\Client;
use SebastianWalker\Paysafecard\Exceptions\ApiError;
use SebastianWalker\Paysafecard\Exceptions\AuthenticationError;
use SebastianWalker\Paysafecard\Exceptions\NotFoundError;
use SebastianWalker\Paysafecard\Exceptions\PaymentError;

class MyClient extends Client
{
    protected static $BASEURL_TESTING = 'https://apitest.paysafecard.com/v1';
    protected static $BASEURL_PRODUCTION = 'https://api.paysafecard.com/v1';
    /**
     * Handle API errors
     *
     * @param $response
     * @throws ApiError
     * @throws AuthenticationError
     * @throws NotFoundError
     * @throws PaymentError
     */
    public function handleError($response)
    {
        $body = $response->body;
        $number = $body->number ?? 'unknown';
        $msg = Lang::get(($hasKey = Lang::has('paysafecard.' . $number)) ?
            'paysafecard.' . $number : 'paysafecard.unknown');
        switch($response->code){
            case 500:
                throw new ApiError($hasKey ? $msg : Lang::get('paysafecard.500'));
            case 401:
                throw new AuthenticationError($hasKey ? $msg : Lang::get('paysafecard.401'));
            case 400:
                if ($hasKey)
                    throw new PaymentError($msg);
                if ($number === 10028)
                    throw new ApiError("Invalid request parameter: ".$body->param." ".$body->message." (10028)");
                throw new ApiError(Lang::get('paysafecard.unknown-number', compact('number')));
            case 404:
                throw new NotFoundError($hasKey ? $msg : Lang::get('paysafecard.404'));
        }
    }
}