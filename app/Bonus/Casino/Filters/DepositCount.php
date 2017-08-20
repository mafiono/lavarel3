<?php

namespace App\Bonus\Casino\Filters;

use App\UserTransaction;

class DepositCount extends Filter
{
    public function run()
    {
        $depositCount = $this->depositCount();

        $this->data = $this->data->filter(function($bonus) use ($depositCount) {
           return $bonus->deposit_count === $depositCount;
        });
    }

    protected function depositCount()
    {
        return UserTransaction::lastestUserDeposits($this->user)
            ->count();
    }
}