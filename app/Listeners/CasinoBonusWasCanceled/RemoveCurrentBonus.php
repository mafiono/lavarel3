<?php

namespace App\Listeners\CasinoBonusWasCanceled;

use App\Events\CasinoBonusWasCancelled;
use CasinoBonus;

class RemoveCurrentBonus
{
    public function handle(CasinoBonusWasCancelled $event)
    {
        CasinoBonus::swapBonus();
    }
}
