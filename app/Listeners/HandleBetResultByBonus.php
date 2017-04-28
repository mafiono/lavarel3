<?php

namespace App\Listeners;

use App\Events\BetWasResulted;
use SportsBonus;

class HandleBetResultByBonus
{
    protected $bet;

    protected $receipt;

    public function handle(BetWasResulted $event)
    {
        $this->bet = $event->bet;

        $this->receipt = $event->receipt;

        $this->handleWageredBonus();

        $this->handleSportBonusCancellations();
    }

    protected function handleSportBonusCancellations()
    {
        if (SportsBonus::isAutoCancellable()) {
            SportsBonus::cancel();
        }
    }

    protected function handleWageredBonus()
    {
        if ($this->bet->status === 'waiting_result' && $this->receipt->amount_bonus > 0) {
            SportsBonus::addWagered($this->receipt->amount_bonus);
        }
    }
}
