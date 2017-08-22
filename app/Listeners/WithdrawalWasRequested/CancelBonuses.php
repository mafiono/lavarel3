<?php

namespace App\Listeners\WithdrawalWasRequested;

use App\Events\WithdrawalWasRequested;
use CasinoBonus;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use SportsBonus;

class CancelBonuses
{
    public function handle(WithdrawalWasRequested $event)
    {
        $this->cancelSports($event->transaction);

        $this->cancelCasino($event->transaction);
    }

    protected function cancelSports($withdrawal)
    {
        if (!SportsBonus::hasActive()) {
            return;
        }

        $withdrawal->user->balance->resetBonus();

        if (SportsBonus::isAutoCancellable()) {
            SportsBonus::cancel();
        }
    }

    protected function cancelCasino($withdrawal)
    {
        if (!CasinoBonus::hasActive()) {
            return;
        }

        if (CasinoBonus::isAutoCancellable()) {
            CasinoBonus::cancel();
        }
    }
}
