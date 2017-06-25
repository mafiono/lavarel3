<?php
/**
 * Created by PhpStorm.
 * User: José.Couto
 * Date: 04/04/2017
 * Time: 18:06
 */

namespace App\Exceptions;

use Exception;

class IdentityException extends CustomException
{
    protected $identityType = 0;
    public function __construct($trans = '', $message = '', $code = 0, Exception $previous = null)
    {
        $pre = 'sign_up.identity.';
        parent::__construct($pre.$trans, $message, $code, $previous);
    }

    /**
     * @param $type
     */
    public function setType($type = 0) {
        $this->identityType = $type;
    }
    public function getType() {
        return $this->identityType;
    }
}