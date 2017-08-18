<?php

namespace App\Listeners\CasinoBonusWasCanceled;

use App\Events\CasinoBonusWasCancelled;

class RemoveCurrentBonus
{
    public function handle(CasinoBonusWasCancelled $event)
    {
        CasinoBonus::swapBonus();
    }
}
