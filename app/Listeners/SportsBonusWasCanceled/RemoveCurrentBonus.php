<?php

namespace App\Listeners\SportsBonusWasCanceled;

use App\Events\SportsBonusWasCancelled;
use SportsBonus;

class RemoveCurrentBonus
{
    public function handle(SportsBonusWasCancelled $event)
    {
        SportsBonus::swapBonus();
    }
}
