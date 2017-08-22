<?php

namespace app\Bonus\Casino;


use App\User;
use App\UserBonus;
use Illuminate\Support\Facades\Facade;

class CasinoBonusFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'casino.bonus';
    }

    public static function swapBonus(UserBonus $bonus = null)
    {
        app()->instance('casino.bonus', self::make(null, $bonus));

        static::swap(app()->make('casino.bonus'));
    }

    public static function swapUser(User $user, UserBonus $bonus = null)
    {
        app()->instance('casino.bonus', self::make($user, $bonus));

        static::swap(app()->make('casino.bonus'));
    }
}