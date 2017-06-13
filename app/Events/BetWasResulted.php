<?php

namespace App\Events;

use App\Bets\Bets\Bet;
use App\Bets\Cashier\BetCashierReceipt;

class BetWasResulted extends Event
{
    public $bet;

    public $receipt;

    public function __construct(Bet $bet, BetCashierReceipt $receipt)
    {
        $this->bet = $bet;

        $this->receipt = $receipt;
    }
}
