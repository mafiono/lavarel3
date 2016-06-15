<?php

namespace App\Http\Controllers;


use App\Bets\Collectors\BetslipCollector;
use App\Bets\Resolvers\FakeBetResolver;

class BetslipController extends Controller
{

    public function placeBets(BetslipCollector $betslip)
    {
        $response = $betslip->collect()->process();

        (new FakeBetResolver())->collectResults()->resolveBets();

        return $response;
    }
}
