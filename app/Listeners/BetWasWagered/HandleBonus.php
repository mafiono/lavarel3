<?php

namespace App\Listeners\BetWasWagered;

use App\Events\BetWasWagered;
use SportsBonus;

class HandleBonus
{
    public function handle(BetWasWagered $event)
    {
        if ($event->receipt->amount_bonus > 0) {
            SportsBonus::addWagered($event->receipt->amount_bonus);
        }

        if (SportsBonus::isAutoCancellable()) {
            SportsBonus::cancel();
        }
    }
}
