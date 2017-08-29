<?php

namespace App\Bonus\Casino;

class Deposit extends BaseCasinoBonus
{
    public function isCancellable()
    {
        return !$this->userBonus()->rounds()->whereRoundstatus('active')->exists();
    }
}