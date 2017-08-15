<?php

namespace App\Bonus;


use App\User;
use App\UserBonus;
use Auth;

abstract class BaseBonus
{
    protected $user;

    protected $userBonus;

    public function __construct(User $user = null, UserBonus $userBonus = null)
    {
        $this->user = $user ?? Auth::user();

        $this->userBonus = $userBonus
            ?? $this->user ? $this->getActive() : null;
    }

    abstract public static function make(User $user = null, UserBonus $userBonus = null);

    abstract public function swapBonus(UserBonus $bonus = null);

    abstract public function swapUser(User $user, UserBonus $bonus = null);

    abstract public function getAvailable($columns = ['*']);

    abstract public function getActive($columns = ['*']);

    abstract public function getConsumed($columns = ['*']);

    abstract public function redeem($bonusId);

    abstract public function cancel();
}