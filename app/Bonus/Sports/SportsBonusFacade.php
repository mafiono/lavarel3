<?php

namespace App\Bonus\Sports;

use App\User;
use App\UserBonus;
use Illuminate\Support\Facades\Facade;

class SportsBonusFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sports.bonus';
    }

    public static function swapBonus(UserBonus $bonus = null)
    {
        app()->instance('sports.bonus', self::make(null, $bonus));

        static::swap(app()->make('sports.bonus'));
    }

    public static function swapUser(User $user, UserBonus $bonus = null)
    {
        app()->instance('sports.bonus', self::make($user, $bonus));

        static::swap(app()->make('sports.bonus'));
    }
}
