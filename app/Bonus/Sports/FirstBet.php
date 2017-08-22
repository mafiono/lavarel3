<?php

namespace App\Bonus\Sports;

use App\Bonus;
use App\UserBet;
use App\UserTransaction;
use Carbon\Carbon;

class FirstBet extends BaseSportsBonus
{

    public function isPayable()
    {
        return $this->userBonus->deposited === 1
            && $this->userBonus->bonus_wagered >= $this->userBonus->rollover_amount
            && !$this->hasUnresolvedBets();
    }

    public function deposit()
    {
        $bonusAmount = $this->redeemAmount();

        $initial_bonus = $this->user->balance->balance_bonus;
        $this->user->balance->addBonus($bonusAmount);

        $this->userBonus->bonus_value = $bonusAmount;
        $this->userBonus->deposited = 1;
        $this->userBonus->save();

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
        return $this->user->balance->fresh()->balance_bonus*1 == 0
            && parent::isAutoCancellable();
    }

    public function redeemAmount(Bonus $bonus = null)
    {
        $latestDeposit = UserTransaction::depositsFromUser($this->user->id)
            ->whereIn('origin', ['bank_transfer', 'cc', 'mb', 'meo_wallet', 'paypal'])
            ->latest('id')
            ->take(1)
            ->first();

        $bonus = $this->userBonus->bonus ?? $bonus;

        $firstBet = UserBet::firstBetFomUser($this->user->id)
            ->whereStatus('lost')
            ->where('created_at', '>=', $latestDeposit->created_at)
            ->first();

        return min(
            $firstBet->amount * $bonus->value/100,
            $bonus->max_bonus
        );
    }
}
