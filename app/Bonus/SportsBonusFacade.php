<?php

namespace App\Bonus;

use App;
use App\User;
use App\UserBonus;
use Illuminate\Support\Facades\Facade;
use SportsBonus;

class SportsBonusFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sports.bonus';
    }
}
