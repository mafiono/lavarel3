<?php

namespace App\Helpers;

class Helper
{
    public static function getKey(array $arr, string $key, string $default)
    {
        return isset($arr) && isset($arr[$key]) ? $arr[$key] : $default;
    }
    public static function ifTrue(array $arr, string $key, $compare, string $value)
    {
        return isset($arr) && isset($arr[$key]) && $arr[$key] == $compare ? $value : '';
    }
}