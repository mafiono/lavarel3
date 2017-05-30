<?php

namespace App\Listeners\BetWasResulted;

use App\Events\BetWasResulted;
use SportsBonus;

class HandleBonus
{
    public function handle(BetWasResulted $event)
    {
        if (SportsBonus::isAutoCancellable()) {
            SportsBonus::cancel();
        }
    }
}
