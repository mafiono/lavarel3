<?php

namespace App\Bonus;


use App\Bets\Bets\Bet;
use App\Bets\Cashier\ChargeCalculator;
use App\GlobalSettings;
use App\UserTransaction;
use Carbon\Carbon;

class FirstDeposit extends SportsBonus
{
    public function applicableTo(Bet $bet)
    {
        $userBonus = $this->getActive()->first();

        return !is_null($userBonus)
            && ($bet->user->balance->balance_bonus > 0)
            && (new ChargeCalculator($bet))->chargeable()
            && (Carbon::now() <= $userBonus->deadline_date)
            && ($bet->odd >= $userBonus->bonus->min_odd)
            && ($bet->lastEvent()->game_date <= $userBonus->deadline_date)
            && ($userBonus->bonus_wagered < $userBonus->rollover_amount);
    }


    public function notifyDeposit($trans)
    {
        $depositsCount = UserTransaction::deposistsFromUser($this->user->id)->count();

        if ($depositsCount === 1)
        {
            $userBonus = $this->getActive();

            $balance = $this->user->balance;
            $bonusAmount = min($trans->debit * $userBonus->bonus->value * 0.01, GlobalSettings::maxFirstDepositBonus());

            $balance->addBonus($bonusAmount);

            $rolloverAmount = $userBonus->rollover_coefficient * min($bonusAmount + $trans->debit, 2 * GlobalSettings::maxFirstDepositBonus());

            $userBonus->bonus_value = $bonusAmount;
            $userBonus->rollover_amount = $rolloverAmount;
            $userBonus->deposited = 1;

            $this->save();
        }
    }

}