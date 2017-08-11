<?php

namespace App\Bets\Cashier\PaymentCalculator;


use App\Bets\Bets\Bet;

abstract class Calculator
{
    protected $bet;

    protected $transaction;

    protected $bonusAmount = 0;

    protected $balanceAmount = 0;

    public function __construct(Bet $bet)
    {
        $this->bet = $bet;

        $this->transaction = $bet->waitingResultStatus->transaction;

        $this->compute();
    }

    public static function make(Bet $bet)
    {
        if ($bet->user_bonus_id === 0) {
            return new NoBonus($bet);
        }

        switch ($bet->userBonus->bonus->bonus_type_id) {
            case 'first_deposit':
                return new FirstDeposit($bet);
            case 'first_bet':
                return new FirstBet($bet);
        }
    }

    protected abstract function compute();

    public function bonus()
    {
        return $this->bonusAmount;
    }

    public function balance()
    {
        return $this->balanceAmount;
    }
}