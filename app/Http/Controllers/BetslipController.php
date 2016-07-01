<?php

namespace App\Http\Controllers;


use App\Bets\Collectors\BetslipCollector;
use App\Bets\Resolvers\FakeBetResolver;
use App\Testx;
use DB;

class BetslipController extends Controller
{

    public function placeBets(BetslipCollector $betslip)
    {
        $response = $betslip->collect()->process();

//        (new FakeBetResolver())->collectResults()->resolveBets();

        return $response;
    }
}
