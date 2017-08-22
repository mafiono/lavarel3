<?php

namespace App\Bonus\Casino;

use App\Bonus;
use App\Bonus\BaseBonus;
use App\Bonus\Casino\Filters\AvailableBonus;
use App\Bonus\Casino\Filters\CasinoDeposit;
use App\Events\CasinoBonusWasCancelled;
use App\Events\CasinoBonusWasRedeemed;
use App\User;
use App\UserBonus;
use Auth;

abstract class BaseCasinoBonus extends BaseBonus
{
    protected $origin = 'casino';

    protected static function bonus(User $user, UserBonus $userBonus)
    {
        switch (($userBonus->bonus->bonus_type_id)) {
            case 'casino_deposit':
                return new Deposit($user, $userBonus);
        }

        return static::noBonus($user);
    }

    protected static function noBonus(User $user = null)
    {
        return new NoBonus($user);
    }

    protected static function activeUserBonus($userId, $origin = null)
    {
        parent::activeUserBonus($userId, 'sports');
    }

    public function getAvailable($columns = ['*'])
    {
        return (new AvailableBonus($this->user))
            ->filter(new CasinoDeposit($this->user))
            ->data()
            ->except($columns)
        ;
    }

    public function isAvailable($bonusId)
    {
        return !$this->getAvailable()
            ->where('id', $bonusId)
            ->isEmpty();
    }

    public function getActive($columns = ['*'])
    {
        return UserBonus::fromUser($this->user->id)
            ->active()
            ->origin($this->origin)
            ->first($columns);
    }

    public function previewRedeemAmount(Bonus $bonus = null)
    {
        if (!is_null($bonus)) {
            switch (($bonus->bonus_type_id)) {
                case 'casino_deposit':
                    return (new Deposit(Auth::user(), null))->redeemAmount($bonus);
            }
        }

        return 0;
    }

    public function isAutoCancellable()
    {
        return true;
    }

    protected function canceledEvent(UserBonus $userBonus)
    {
        event(new CasinoBonusWasCancelled($userBonus));
    }

    protected function redeemedEvent(UserBonus $userBonus)
    {
        event(new CasinoBonusWasRedeemed($userBonus));
    }

    protected function throwException($message = null)
    {
        throw new CasinoBonusException($message);
    }
}