<?php

namespace App\Bets\Cashier;

use App\UserBet;
use App\UserBetTransaction;

class BetCashierReceipt extends UserBetTransaction
{
    private $bet;

    public function __construct(UserBet $bet)
    {
        $this->bet = $bet;
    }

    public function prepare($operation)
    {
        $this->user_bet_status_id = $this->bet->currentStatus->id;
        $this->initial_balance = $this->bet->user->balance->balance_available;
        $this->initial_bonus = $this->bet->user->balance->balance_bonus;
        $this->operation = $operation;
    }

    public function finalize($amountBalance, $amountBonus)
    {
        $this->amount_balance = $amountBalance;
        $this->amount_bonus = $amountBonus;
        $this->final_balance = $this->bet->user->balance->balance_available;
        $this->final_bonus = $this->bet->user->balance->balance_bonus;

        $this->save();

        if ($this->isBetPortugalApi())
            $this->setBetPortugalApi();
    }

    private function isBetPortugalApi()
    {
        return $this->status->bet->api_bet_type === 'betportugal';
    }

    private function setBetPortugalApi()
    {
        $this->api_transaction_id = $this->id;
        $this->save();

        $this->bet->api_transaction_id = $this->id;
        $this->bet->api_bet_id = $this->bet->id;
        $this->bet->save();
    }

}