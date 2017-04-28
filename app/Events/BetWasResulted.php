<?php

namespace App\Events;

use App\Bets\Bets\Bet;
use App\Bets\Cashier\BetCashierReceipt;
use Illuminate\Queue\SerializesModels;

class BetWasResulted extends Event
{
    use SerializesModels;

    public $bet;

    public $receipt;

    public function __construct(Bet $bet, BetCashierReceipt $receipt)
    {
        $this->bet = $bet;

        $this->receipt = $receipt;
    }
}
