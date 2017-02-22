<?php

namespace App\Bonus;

class FriendInvite extends BaseSportsBonus
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
}
