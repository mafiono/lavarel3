<?php
/**
 * Created by PhpStorm.
 * User: José.Couto
 * Date: 04/04/2017
 * Time: 18:06
 */

namespace App\Exceptions;

use Exception;

class SelfExclusionException extends CustomException
{
    public function __construct($trans = "", $message = "", $code = 0, Exception $previous = null)
    {
        $pre = 'self_exclusion.errors.';
        parent::__construct($pre.$trans, $message, $code, $previous);
    }
}