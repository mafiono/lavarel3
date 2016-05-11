<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Bets\Collectors\BetslipCollector;
use App\Bets\Resolvers\FakeBetResolver;
use Auth;


class BetslipController extends Controller
{
    public function placeBets(Request $request)
    {
        $betslip = new BetslipCollector(Auth::user(), $request);

        $response = $betslip->processBets($betslip->collectBets());

        (new FakeBetResolver())->collectResults()->resolveBets();

        return $response;
    }
}
