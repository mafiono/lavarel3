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

    public static function make(User $user = null, UserBonus $userBonus = null)
    {
        $user = $user ? $user : Auth::user();

        if (is_null($user)) {
            return static::noBonus();
        }

        $activeBonus = $userBonus ?: UserBonus::activeFromUser($user->id, ['bonus'])->first();

        if (is_null($activeBonus)) {
            return static::noBonus($user);
        }

        if ($user->id != $activeBonus->user_id)
            throw new \Exception("WTF");

        return static::bonus($user, $activeBonus);
    }

    abstract protected static function bonus(User $user, UserBonus $userBonus);

    abstract protected static function noBonus(User $user = null);

    abstract public function getAvailable($columns = ['*']);

    abstract public function getActive($columns = ['*']);

    abstract public function getConsumed($columns = ['*']);

    abstract public function redeem($bonusId);

    abstract public function cancel();
}