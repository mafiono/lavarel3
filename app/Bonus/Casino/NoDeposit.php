<?php

namespace App\Bonus\Casino;

use App\Bonus;

class NoDeposit extends Deposit
{
    protected function deposited()
    {
        return false;
    }

    public function redeemAmount(Bonus $bonus = null)
    {
        $bonus = $this->userBonus->bonus ?? $bonus;

        return $bonus->value;
    }
}