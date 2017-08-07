<?php

namespace app\Bets\Cashier\PaymentCalculator;


class FirstBet extends Calculator
{
    protected function compute()
    {
        $amount = $this->transaction->amount_bonus * $this->bet->odd;

        if ($this->hasMinimumWinningEvents()) {
            $this->balanceAmount = $amount;
        } else {
            $this->bonusAmount = $amount;
        }
    }

    protected function hasMinimumWinningEvents()
    {
        return $this->bet->events->where('status', 'won')->count() > 2;
    }
}