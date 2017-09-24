<?php

namespace App\Http\Controllers;

use App\Bets\Collectors\BetslipCollector;
use Illuminate\Http\Request;

class BetslipController extends Controller
{

    public function placeBets(BetslipCollector $betslip)
    {
        sleep (config('app.bet_submit_delay'));

        $response = $betslip->collect()->process();

        return $response;
    }

    public function addBets(Request $request)
    {
        if ($request->has('bets')) {
            session()->flash('bets', explode(',', $request->bets));
        }

        return redirect('/');
    }
}
