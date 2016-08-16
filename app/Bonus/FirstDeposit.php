<?php

namespace App\Bonus;


use App\UserBet;
use Carbon\Carbon;



class FirstDeposit extends SportsBonus
{
    public function applicableTo(UserBet $bet)
    {
        $userBonus = static::getActive();

        return !is_null($userBonus) &&
        ($bet->user->balance->balance_bonus > 0) &&
        (Carbon::now() <= $userBonus->deadline_date) &&
        ($bet->odd >= $userBonus->bonus->min_odd) &&
        ($bet->lastEventDate() <= $userBonus->deadline_date);
    }
}