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
        sleep (config('app.bet_submit_delay'));

        $response = $betslip->collect()->process();

        return $response;
    }
}
