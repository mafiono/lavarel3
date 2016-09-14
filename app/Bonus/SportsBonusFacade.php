<?php

namespace App\Bonus;

use Illuminate\Support\Facades\Facade;

class SportsBonusFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sportsBonus';
    }
}