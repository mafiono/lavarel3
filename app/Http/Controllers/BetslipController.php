<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Bets\Collectors\BetslipCollector;
use Auth;


class BetslipController extends Controller
{
    public function placeBets(Request $request)
    {
        $betslip = new BetslipCollector(Auth::user(), $request);

        return $betslip->processBets($betslip->collectBets());
    }
}
