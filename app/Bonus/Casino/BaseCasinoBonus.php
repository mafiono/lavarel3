<?php

namespace App\Bonus\Casino;


use App\User;
use App\UserBonus;
use Auth;

abstract class BaseCasinoBonus
{
    protected $user;

    protected $userBonus;

    public function __construct(User $user = null, UserBonus $userBonus = null)
    {
        $this->user = $user ?? Auth::user();

        $this->userBonus = $userBonus
            ?? $this->user ? $this->getActive() : null;
    }

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