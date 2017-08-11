<?php

namespace app\Bets\Cashier\PaymentCalculator;


class FirstDeposit extends Calculator
{
    protected function compute()
    {
        $this->balanceAmount = $this->transaction->amount_balance * $this->bet->odd
            + $this->transaction->amount_bonus * $this->bet->odd;
    }
}