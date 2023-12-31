<?php

namespace App\Bonus\Casino;

use App\Bonus;
use App\Bonus\BaseBonus;
use App\Bonus\Casino\Filters\AvailableBonus;
use App\Bonus\Casino\Filters\CasinoDeposit;
use App\Bonus\Casino\Filters\CasinoNoDeposit;
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
            case 'casino_no_deposit':
                return new NoDeposit($user, $userBonus);
        }

        return static::noBonus($user);
    }

    protected static function noBonus(User $user = null)
    {
        return new NoBonus($user);
    }

    protected static function activeUserBonus($userId, $origin = null)
    {
        return parent::activeUserBonus($userId, 'casino');
    }

    public function getAvailable($columns = ['*'])
    {
        return (new AvailableBonus($this->user))
            ->combine([
                new CasinoDeposit($this->user),
                new CasinoNoDeposit($this->user)]
            )->data()
            ->except($columns)
            ;
    }

    public function isAvailable($bonusId)
    {
        return $this->getAvailable()
                ->where('id', $bonusId*1)
                ->count() > 0;
    }

    public function getActive($columns = ['*'])
    {
        return UserBonus::fromUser($this->user->id)
            ->active()
            ->origin($this->origin)
            ->first($columns);
    }

    public function hasAvailable()
    {
        return $this->getAvailable()
            ->count() > 0;
    }

    public function previewRedeemAmount(Bonus $bonus = null)
    {
        if (!is_null($bonus)) {
            switch (($bonus->bonus_type_id)) {
                case 'casino_deposit':
                    return (new Deposit(Auth::user(), null))->redeemAmount($bonus);
                case 'casino_no_deposit':
                    return (new NoDeposit(Auth::user(), null))->redeemAmount($bonus);
            }
        }

        return 0;
    }

    public function isCancellable()
    {
        return !$this->userBonus()->rounds()->whereRoundstatus('active')->exists();
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