<?php

namespace App\Bets\Bookie;

use App\UserBet;
use App\Bets\Cashier\BetCashier;

class BetBookie {

    public static function placeBet(UserBet $bet) {
        $bet->placeBet();
        BetCashier::charge($bet);
    }

    public static function setWonResult(UserBet $bet) {
        $bet->setWonResult();
        BetCashier::pay($bet);
    }

    public static function setLostResult(UserBet $bet) {
        $bet->setLostResult();
    }

    public static function cancelBet(UserBet $bet) {
        $bet->cancelBet();
        BetCashier::refund($bet);
    }


}