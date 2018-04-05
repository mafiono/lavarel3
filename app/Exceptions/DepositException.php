<?php
/**
 * Created by PhpStorm.
 * User: José.Couto
 * Date: 04/04/2017
 * Time: 18:06
 */

namespace App\Exceptions;

use Exception;

class DepositException extends CustomException
{
    public function __construct($trans = '', $message = '', $code = 0, Exception $previous = null)
    {
        $pre = 'deposits.errors.';
        parent::__construct($pre.$trans, $message, $code, $previous);
    }
}