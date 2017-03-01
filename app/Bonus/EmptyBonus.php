<?php

namespace App\Bonus;

use App\Bets\Bets\Bet;

class EmptyBonus extends BaseSportsBonus
{
    public function cancel()
    {
        $this->throwException();
    }

    public function forceCancel()
    {
        $this->throwException();
    }

    public function isCancellable()
    {
        return false;
    }

    public function isAutoCancellable()
    {
        return false;
    }

    public function addWagered($amount)
    {
        $this->throwException();
    }

    public function subtractWagered($amount)
    {
        $this->throwException();
    }

    public function applicableTo(Bet $bet)
    {
        return false;
    }

    public function pay()
    {
        $this->throwException();
    }

    protected function throwException()
    {
        throw new SportsBonusException('No active bonus.');
    }
}
