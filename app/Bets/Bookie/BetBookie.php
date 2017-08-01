<?php

namespace App\Bets\Bookie;

use App\Bets;
use App\Bets\Bets\Bet;
use App\Bets\Cashier\BetCashier;

class BetBookie {

    public static function placeBet(Bet $bet, $sessionId = null)
    {
        $bet->placeBet($sessionId);
        BetCashier::charge($bet, true);
    }

    public static function wonResult(Bet $bet) {
        $bet->setWonResult();
        BetCashier::pay($bet);
    }

    public static function lostResult(Bet $bet) {
        $bet->setLostResult();
        BetCashier::noPay($bet);
    }

    public static function returnBet(Bet $bet) {
        $bet->returnBet();
        BetCashier::refund($bet);
    }

    public static function wonPartial(Bet $bet) {
        $bet->calcPartialOdd()->setWonResult();
        BetCashier::pay($bet);
    }
}