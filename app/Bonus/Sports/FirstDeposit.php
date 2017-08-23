<?php

namespace App\Bonus\Sports;

use App\Bonus;
use App\UserTransaction;
use Carbon\Carbon;

class FirstDeposit extends BaseSportsBonus
{
    public function isAutoCancellable()
    {
        return $this->user->balance->fresh()->balance_bonus*1 < 0.2
            && parent::isAutoCancellable();
    }
}
