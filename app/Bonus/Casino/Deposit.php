<?php

namespace App\Bonus\Casino;

use App\UserTransaction;
use Carbon\Carbon;

class Deposit extends BaseCasinoBonus
{
    public function deposit()
    {
        $bonusAmount = $this->redeemAmount();

        $initial_bonus = $this->user->balance->balance_bonus;
        $this->user->balance->addBonus($bonusAmount);

        $this->userBonus->update([
            'bonus_value' => $bonusAmount,
            'deposited' => 1,
        ]);

        UserTransaction::forceCreate([
            'user_id' => $this->user->id,
            'origin' => 'casino_bonus',
            'transaction_id' => UserTransaction::getHash($this->user->id, Carbon::now()),
            'debit_bonus' => $bonusAmount,
            'initial_balance' => $this->user->balance->balance_available,
            'initial_bonus' => $initial_bonus,
            'final_balance' => $this->user->balance->balance_available,
            'final_bonus' => $this->user->balance->balance_bonus,
            'date' => Carbon::now(),
            'description' => 'Resgate de bónus ' . $this->userBonus->bonus->title,
            'status_id' => 'processed',
        ]);
    }
}