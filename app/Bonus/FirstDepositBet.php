<?php

namespace App\Bonus;

use App\Bets\Bets\Bet;
use App\GlobalSettings;
use App\UserBet;

class FirstDepositBet extends BaseSportsBonus
{

    public function isPayable()
    {
        return $this->userBonus->deposited === 1
            && $this->userBonus->bonus_wagered >= $this->userBonus->rollover_amount
            && !$this->hasUnresolvedBets();
    }

    public function applicableTo(Bet $bet)
    {
        return ($bet->type === 'multi')
            && parent::applicableTo($bet)
            && !$this->hasUnresolvedBets([$bet->id]);
    }

    public function deposit()
    {
        $firstBet = UserBet::firstBetFomUser($this->user->id)
            ->notReturned()
            ->first();

        $bonusAmount = min($firstBet->amount * 0.5, GlobalSettings::maxFirstDepositBonus());

        $this->user->balance->addBonus($bonusAmount);

        $this->userBonus->bonus_value = $bonusAmount;
        $this->userBonus->deposited = 1;
        $this->userBonus->save();
    }

    public function isAutoCancellable()
    {
        return $this->user->balance->fresh()->balance_bonus*1 == 0
            && parent::isAutoCancellable();

    }
}