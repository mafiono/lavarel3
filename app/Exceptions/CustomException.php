<?php
/**
 * Created by PhpStorm.
 * User: José.Couto
 * Date: 04/04/2017
 * Time: 18:06
 */

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{
    public function __construct($key = '', $message = '', $code = 0, Exception $previous = null)
    {
        $trans = app('translator');
        if ($trans->has($key))
            $message = $trans->get($key);

        parent::__construct($message, $code, $previous);
    }
}