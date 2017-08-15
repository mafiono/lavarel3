<?php

namespace App\Bonus\Casino;


use App\Bonus\BaseBonus;
use App\User;
use App\UserBonus;

abstract class BaseCasinoBonus extends BaseBonus
{
    public static function make(User $user = null, UserBonus $bonus = null)
    {

    }

    public function swapBonus(UserBonus $bonus = null)
    {
        app()->instance('sports.bonus', self::make($this->user, $bonus));

        self::swap(app()->make('sports.bonus'));
    }

    public function swapUser(User $user, UserBonus $bonus = null)
    {
        app()->instance('sports.bonus', BaseSportsBonus::make($user, $bonus));

        SportsBonus::swap(app()->make('sports.bonus'));
    }


    public function getAvailable($columns = ['*'])
    {

    }

    public function getActive($columns = ['*'])
    {
        return UserBonus::fromUser($this->user->id)
            ->active()
            ->first($columns);
    }

    public function getConsumed($columns = ['*'])
    {
    }

    public function redeem($bonusId)
    {

    }

    public function cancel()
    {
    }

}