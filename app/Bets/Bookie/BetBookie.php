<?php

namespace App\Bets\Bookie;

use App\Bets;
use app\Bets\Bets\Bet;
use App\Bets\Cashier\BetCashier;

class BetBookie {

    public static function placeBet(Bet $bet)
    {
        $bet->placeBet();
        BetCashier::charge($bet, true);
    }

    public static function setWonResult(Bet $bet) {
        $bet->setWonResult();
        BetCashier::pay($bet);
    }

    public static function setLostResult(Bet $bet) {
        $bet->setLostResult();
        BetCashier::noPay($bet);
    }

    public static function cancelBet(Bet $bet) {
        $bet->cancelBet();
        BetCashier::refund($bet);
    }

}