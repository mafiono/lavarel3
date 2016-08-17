<?php

namespace App\Bonus;


use App\Bets\Bets\Bet;
use App\Bets\Cashier\ChargeCalculator;
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
            && ($bet->lastEvent()->game_date <= $userBonus->deadline_date);
    }

    public function foo()
    {
        return "firstDeposit foo";
    }
}