<?php

namespace App\Traits;

trait MainDatabase
{
    public static function alias($alias) {
        return (new static)->table . ' as ' . $alias;
    }
}
