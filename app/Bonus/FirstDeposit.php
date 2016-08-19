<?php

namespace App\Bonus;


use App\Bets\Bets\Bet;
use App\Bets\Cashier\ChargeCalculator;
use App\GlobalSettings;
use App\UserBonus;
use App\UserTransaction;
use Carbon\Carbon;
use Lang;

class FirstDeposit extends BaseSportsBonus
{
    public function cancel()
    {
        $this->__selfExcludedCheck();

        if (!$this->isCancellable())
            throw new SportsBonusException(Lang::get('bonus.cancel.error'));

        $this->__deactivate();
    }

    public function isCancellable()
    {
        return !$this->__hasUnresolvedBets();
    }

    public function isAutoCancellable()
    {
        $balance = $this->_user->balance->fresh();

        return $this->_userBonus->deposited === 1
            && $balance->balance_bonus === 0
            && $this->isCancellable();
    }

    public function isPayable()
    {
        return $this->_userBonus->deposited === 1
            && $this->_userBonus->bonus_wagered >= $this->_userBonus->rollover_amount
            && !$this->__hasUnresolvedBets()
            && (Carbon::now() <= $this->_userBonus->deadline_date);
    }

    public function addWagered($amount)
    {
        $this->_userBonus = UserBonus::lockForUpdate()->find($this->_userBonus->id);
        $this->_userBonus->bonus_wagered += $amount;
        $this->_userBonus->save();
    }

    public function subtractWagered($amount)
    {
        $this->_userBonus = UserBonus::lockForUpdate()->find($this->_userBonus->id);
        $this->_userBonus->bonus_wagered -= $amount;
        $this->_userBonus->save();
    }

    public function applicableTo(Bet $bet)
    {
        return ($bet->user->balance->balance_bonus > 0)
            && (new ChargeCalculator($bet))->chargeable()
            && (Carbon::now() <= $this->_userBonus->deadline_date)
            && ($bet->odd >= $this->_userBonus->bonus->min_odd)
            && ($bet->lastEvent()->game_date <= $this->_userBonus->deadline_date)
            && ($this->_userBonus->bonus_wagered < $this->_userBonus->rollover_amount);
    }

    public function depositNotify($trans)
    {
        $depositsCount = UserTransaction::deposistsFromUser($this->_user->id)->count();
        if ($depositsCount === 1)
        {
            $balance = $this->_user->balance->fresh();
            $bonusAmount = min($trans->debit * $this->_userBonus->bonus->value * 0.01, GlobalSettings::maxFirstDepositBonus());
            $balance->addBonus($bonusAmount);
            $rolloverAmount = $this->_userBonus->rollover_coefficient * min($bonusAmount + $trans->debit, 2 * GlobalSettings::maxFirstDepositBonus());
            $this->_userBonus->bonus_value = $bonusAmount;
            $this->_userBonus->rollover_amount = $rolloverAmount;
            $this->_userBonus->deposited = 1;
            $this->_userBonus->save();
        }
    }

    public function pay($amount)
    {
        $balance = $this->_user->balance->fresh();
        $balance->addAvailableBalance($balance->balance_bonus);

        $this->__deactivate();

        UserTransaction::createTransaction(
            $balance->balance_bonus,
            $this->_user->id,
            'BONUS'.$this->_userBonus->id,
            'deposit',
            null,
            null
        );
    }

    public function foo()
    {
        return 'first deposit';
    }

}