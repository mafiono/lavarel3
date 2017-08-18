<?php

namespace App\Bonus\Sports;

use Illuminate\Support\Facades\Facade;

class SportsBonusFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sports.bonus';
    }
}
