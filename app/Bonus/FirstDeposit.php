<?php

namespace App\Bonus;

use App\Bets\Bets\Bet;
use App\GlobalSettings;
use App\UserTransaction;
use Carbon\Carbon;

class FirstDeposit extends BaseSportsBonus
{

    public function isPayable()
    {
        return $this->userBonus->deposited === 1
            && $this->userBonus->bonus_wagered >= $this->userBonus->rollover_amount
            && !$this->hasUnresolvedBets()
            && (Carbon::now() <= $this->userBonus->deadline_date);
    }

    public function applicableTo(Bet $bet)
    {
        return ($bet->type === 'multi')
            && parent::applicableTo($bet)
            && ($this->userBonus->bonus_wagered < $this->userBonus->rollover_amount);
    }

    public function deposit()
    {
        $trans = UserTransaction::whereUserId($this->user->id)
            ->whereStatusId('processed')
            ->latest('id')
            ->first();

        $bonusAmount = min(
            $trans->debit * $this->userBonus->bonus->value * 0.01,
            GlobalSettings::maxFirstDepositBonus()
        );

        $this->user->balance->addBonus($bonusAmount);

        $rolloverAmount = $this->userBonus->bonus->rollover_coefficient *
            min($bonusAmount + $trans->debit, 2 * GlobalSettings::maxFirstDepositBonus());

        $this->userBonus->bonus_value = $bonusAmount;
        $this->userBonus->rollover_amount = $rolloverAmount;
        $this->userBonus->deposited = 1;
        $this->userBonus->save();
    }

    public function pay()
    {
        $balance = $this->user->balance->fresh();

        $initialBalance = $balance->balance_available;
        $balance->addAvailableBalance($balance->balance_bonus);
        $finalBalance = $balance->balance_available;

        $this->deactivate();

        $trans = UserTransaction::createTransaction(
            $balance->balance_bonus,
            $this->user->id,
            'SportsBonus',
            'deposit',
            null,
            null
        );

        UserTransaction::updateTransaction(
            $this->user->id,
            $trans->transaction_id,
            $balance->balance_bonus,
            'processed',
            null,
            null,
            'FIRST_DEPOSIT bonus nº'.$this->userBonus->id,
            $initialBalance,
            $finalBalance
        );
    }
}
