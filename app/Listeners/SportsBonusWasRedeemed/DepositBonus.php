<?php

namespace App\Listeners\SportsBonusWasRedeemed;

use App\Events\SportsBonusWasRedeemed;
use SportsBonus;

class DepositBonus
{
    public function handle(SportsBonusWasRedeemed $event)
    {
        SportsBonus::swapBonus($event->userBonus);

        SportsBonus::deposit();
    }
}
