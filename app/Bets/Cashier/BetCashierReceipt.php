<?php

namespace App\Bets\Cashier;

use app\Bets\Bets\Bet;
use App\UserBetTransaction;

class BetCashierReceipt extends UserBetTransaction
{
    private $bet;

    public function __construct(Bet $bet, array $attributes = null)
    {
        $this->bet = $bet;

        $this->init();
    }

    public static function make(Bet $bet)
    {
        return new static($bet);
    }

    public static function makeDeposit(Bet $bet)
    {
        $receipt = static::make($bet);

        $receipt->operation = "deposit";

        return $receipt;
    }

    public static function makeWithdrawal(Bet $bet)
    {
        $receipt = static::make($bet);

        $receipt->operation = "withdrawal";

        return $receipt;
    }

    private function init()
    {
        $this->user_bet_status_id = $this->bet->currentStatus->id;
        $this->initial_balance = $this->bet->user->balance->balance_available;
        $this->initial_bonus = $this->bet->user->balance->balance_bonus;
    }

    public function store()
    {
        $this->final_balance = $this->bet->user->balance->balance_available;
        $this->final_bonus = $this->bet->user->balance->balance_bonus;

        $result = $this->save();

        if ($this->bet->bet_api_type === 'betportugal') {
            $this->api_transaction_id = $this->id;

            $this->save();
        }

        return $result;
    }

}