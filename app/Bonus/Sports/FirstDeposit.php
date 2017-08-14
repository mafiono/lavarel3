<?php

namespace App\Bonus\Sports;

use App\Bonus;
use App\UserTransaction;
use Carbon\Carbon;

class FirstDeposit extends BaseSportsBonus
{
    public function deposit()
    {
        $bonusAmount = $this->bonusAmount($this->userBonus->bonus);

        $initial_bonus = $this->user->balance->balance_bonus;
        $this->user->balance->addBonus($bonusAmount);

        $this->userBonus->update([
            'bonus_value' => $bonusAmount,
            'deposited' => 1,
        ]);

        UserTransaction::forceCreate([
            'user_id' => $this->user->id,
            'origin' => 'sport_bonus',
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

    public function isAutoCancellable()
    {
        return $this->user->balance->fresh()->balance_bonus*1 < 0.2
            && parent::isAutoCancellable();
    }

    public function bonusAmount(Bonus $bonus = null)
    {
        $trans = UserTransaction::whereUserId($this->user->id)
            ->whereIn('origin', ['bank_transfer', 'cc', 'mb', 'meo_wallet', 'paypal'])
            ->whereStatusId('processed')
            ->latest('id')
            ->first();

        $bonus = $this->userBonus->bonus ?? $bonus;

        return min(
            $trans->debit * $bonus->value * 0.01,
            $bonus->max_bonus
        );
    }
}
