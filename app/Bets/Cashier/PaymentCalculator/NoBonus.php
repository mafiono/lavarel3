<?php

namespace app\Bets\Cashier\PaymentCalculator;


class NoBonus extends Calculator
{
    protected function compute()
    {
        $this->balanceAmount = $this->transaction->amount_balance * $this->bet->odd;
    }
}