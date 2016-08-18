<?php

namespace App\Bonus;


use App\Bets\Bets\Bet;
use App\Bets\Cashier\ChargeCalculator;
use App\GlobalSettings;
use App\UserTransaction;
use Carbon\Carbon;

class FirstDeposit extends SportsBonus
{
    public function foo()
    {
        return 'first deposit';
    }
}