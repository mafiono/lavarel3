<?php

namespace App\Bonus\Casino;

use App\UserTransaction;
use Carbon\Carbon;

class Deposit extends BaseCasinoBonus
{
    public function isCancellable()
    {
        return true;
    }
}