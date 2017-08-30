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
            && !$this->hasUnresolvedBets();
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
