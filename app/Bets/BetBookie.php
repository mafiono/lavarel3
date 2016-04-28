<?php

namespace App\Bets;

use App\UserBet;
use App\UserBetTransactions;

class BetBookie {

    public static function placeBet(Bet $bet) {
        $userBet = UserBet::createBet($bet);
        $receipt = BetCollector::charge($bet, $userBet);
        UserBetTransactions::createTransaction($bet, $receipt, 'withdrawal', $userBet->currentStatus->id);
        return $userBet;
    }

    public static function setWonResult(Bet $bet) {
        $userBet = UserBet::findByApi($bet->getApiType(), $bet->getApiId());
        $userBet->setWonResult();
        $receipt = BetCollector::pay($bet, $userBet);
        UserBetTransactions::createTransaction($bet, $receipt, 'deposit', $userBet->currentStatus->id);
        return $userBet;
    }

    public static function setLostResult(Bet $bet) {
        $userBet = UserBet::findByApi($bet->getApiType(), $bet->getApiId());
        $userBet->setLostResult();
        UserBetStatus::setBetStatus($userBet);
        return $userBet;
    }

    public static function cancelBet(Bet $bet) {
        $userBet = UserBet::findByApi($bet->getApiType(), $bet->getApiId());
        $userBet->cancel();
        $receipt = BetCollector::refund($bet);
        UserBetTransactions::createTransaction($bet, $receipt, 'deposit', $userBet->currentStatus->id);
        return $userBet;
    }


}