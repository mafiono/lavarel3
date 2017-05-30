<?php

namespace App\Events;

use App\UserTransaction;

class WithdrawalWasRequested extends Event
{
    public $transaction;

    public function __construct(UserTransaction $transaction)
    {
        $this->transaction = $transaction;
    }
}
