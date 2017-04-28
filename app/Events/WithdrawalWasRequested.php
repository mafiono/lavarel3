<?php

namespace App\Events;

use App\UserTransaction;
use Illuminate\Queue\SerializesModels;

class WithdrawalWasRequested extends Event
{
    use SerializesModels;

    public $transaction;

    public function __construct(UserTransaction $transaction)
    {
        $this->transaction = $transaction;
    }
}
