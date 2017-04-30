<?php

namespace App\Listeners\WithdrawalWasRequested;

use App\Events\WithdrawalWasRequested;
use SportsBonus;

class HandleBonus
{
    public function handle(WithdrawalWasRequested $event)
    {
        if (!SportsBonus::hasActive()) {
            return;
        }

        $event->transaction->user->balance->resetBonus();

        if (SportsBonus::isAutoCancellable()) {
            SportsBonus::cancel();
        }
    }
}
