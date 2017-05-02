<?php

namespace App\Helpers;

class Helper
{
    public static function getKey(array $arr, string $key)
    {
        return isset($arr) && isset($arr[$key]) ? $arr[$key] : '';
    }
    public static function ifTrue(array $arr, string $key, $compare, string $value)
    {
        return isset($arr) && isset($arr[$key]) && $arr[$key] == $compare ? $value : '';
    }
}