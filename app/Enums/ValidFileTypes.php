<?php

namespace App\Enums;
/**
 * Class ValidFileTypes
 * @package App\Enums
 */
class ValidFileTypes
{
    public static $all = [
        'image/png', 'image/jpeg', 'image/gif',
        'application/pdf',
        'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];

    public static function isValid($type) {
        return in_array($type, self::$all, true);
    }
}