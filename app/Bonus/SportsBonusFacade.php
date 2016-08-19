<?php

namespace App\Bonus;

use App;
use App\User;
use Illuminate\Support\Facades\Facade;
use SportsBonus;

class SportsBonusFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sports.bonus';
    }

    public static function swapUser(User $user)
    {
        App::instance('sports.bonus', BaseSportsBonus::make($user));

        SportsBonus::swap(App::make('sports.bonus'));
    }

}