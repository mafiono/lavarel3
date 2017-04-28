<?php

namespace App\Listeners;

use App\Events\WithdrawalWasRequested;
use App\UserBalance;
use SportsBonus;

class HandleWithdrawalWasRequestedByBonus
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
