<?php

namespace App\Bets\Resolvers;

use app\Bets\Bets\Bet;
use App\Bets\Bookie\BetBookie;
use App\Bets\Bets\BetResultFaker;
use Exception;
use DB;


class FakeBetResolver extends BetResolver
{

    public function __construct()
    {
    }

    /**
     * @throws Exception
     */
    public function collectResults()
    {
        $bets = Bet::fetchUnresolvedBets();

        foreach ($bets as $bet)
            $this->bets[] = BetResultFaker::setResult($bet);

        return $this;
    }

    public function resolveBets()
    {
        foreach ($this->bets as $bet) {
            DB::transaction(function () use ($bet) {
//                try {
                    $this->resolveBet($bet);
//                } catch (Exception $e) {
//                    dd($e->getTraceAsString());
//                    throw new Exception('Oh noes: '.$e->getTraceAsString());
                    //TODO: log this problem
//                }
            });
        }

        return $this;
    }

    /**
     * @param $bet
     */
    private function resolveBet($bet)
    {
        if ($bet->status === 'won')
            BetBookie::setWonResult($bet);
        if ($bet->status === 'lost')
            BetBookie::setLostResult($bet);
    }
}