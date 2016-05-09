<?php


namespace App\Bets\Collectors;

use App\Bets\Bookie\BetBookie;
use App\Bets\Bets\BetResultFaker;


class BetPortugalCollector extends BetCollector
{

    public function collectBets()
    {
        $this->bets = BetResultFaker::fetchUnresolvedBets();
    }

    public function processBets()
    {
        foreach ($this->bets as $bet)
            $this->processBet($bet);
    }

    private function processBet(UserBet $bet)
    {
        if ($bet->status === 'won')
            BetBookie::setWonResult($bet);
        if ($bet->status === 'lost')
            BetBookie::setLostResult($bet);
    }
}