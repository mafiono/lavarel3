<?php

namespace App\Listeners\CasinoBonusWasRedeemed;

use App\Events\CasinoBonusWasRedeemed;
use CasinoBonus;

class DepositBonus
{
    public function handle(CasinoBonusWasRedeemed $event)
    {
        CasinoBonus::swapBonus($event->userBonus);

        CasinoBonus::deposit();
    }
}
