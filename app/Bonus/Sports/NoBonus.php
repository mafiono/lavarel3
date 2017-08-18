<?php

namespace App\Bonus\Sports;

use App\Bets\Bets\Bet;

class NoBonus extends BaseSportsBonus
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

    public function applicableTo(Bet $bet, $reason = false)
    {
        return false;
    }

    public function isAppliedToBet(Bet $bet)
    {
        return false;
    }

    public function pay()
    {
        $this->throwException();
    }

    protected function throwException($message = null)
    {
        throw new SportsBonusException('No active bonus.');
    }
}
