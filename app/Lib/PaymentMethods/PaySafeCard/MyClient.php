<?php

namespace App\Lib\PaymentMethods\PaySafeCard;

use SebastianWalker\Paysafecard\Client;
use SebastianWalker\Paysafecard\Exceptions\ApiError;
use SebastianWalker\Paysafecard\Exceptions\AuthenticationError;
use SebastianWalker\Paysafecard\Exceptions\NotFoundError;
use SebastianWalker\Paysafecard\Exceptions\PaymentError;

class MyClient extends Client
{
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
        switch($response->code){
            case 500:
                throw new ApiError("Technical error on Paysafecard's end");
            case 401:
                throw new AuthenticationError("Authentication failed due to missing or invalid API key (10008)");
            case 400:
                $number = $body->number;
                switch($number){
                    case 10028:
                        throw new ApiError("Invalid request parameter: ".$body->param." ".$body->message." (10028)");
                    case 2001:
                        throw new PaymentError("Transaction already exists (2001)");
                    case 2017:
                        throw new PaymentError("This payment is not capturable at the moment (2017)");
                    case 3001:
                        throw new PaymentError("Merchant is not active (3001)");
                    case 3007:
                        throw new PaymentError("Debit attempt after expiry of dispo time window (3007)");
                    case 3195:
                        throw new PaymentError("Customer details from request don't match with database (3195)");
                    default:
                        throw new ApiError("Unknown error (".$number.")");
                }
            case 404:
                throw new NotFoundError("Resource not found");
        }
    }
}