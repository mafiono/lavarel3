<?php

namespace App\Bonus\Casino;

class NoBonus extends BaseCasinoBonus
{
    public function deposit()
    {
    }

    public function isCancellable()
    {
        return false;
    }
}