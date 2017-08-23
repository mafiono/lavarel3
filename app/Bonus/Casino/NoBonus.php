<?php

namespace App\Bonus\Casino;

class NoBonus extends BaseCasinoBonus
{
    public function isSuspended()
    {
        return true;
    }

    public function suspend()
    {
    }

    public function deposit()
    {
    }
}