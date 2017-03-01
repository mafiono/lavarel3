<?php

namespace App\Bonus;

use App\Bets\Bets\Bet;
use App\UserBet;

class FreeBet extends BaseSportsBonus
{
    public function deposit()
    {
        $bonusAmount = $this->userBonus->bonus->value*1;

        $this->user->balance->addBonus($bonusAmount);

        $this->userBonus->update([
            'bonus_value' => $bonusAmount,
            'deposited' => 1,
        ]);
    }

    public function applicableTo(Bet $bet)
    {
        return ($bet->amount <= $this->userBonus->bonus_value)
            && parent::applicableTo($bet);
    }

    protected function betWonWithFreeBetBonus()
    {
        return UserBet::fromUser($this->user->id)
            ->whereStatus('won')
            ->fromBonus($this->userBonus->id)
            ->first();
    }
}
