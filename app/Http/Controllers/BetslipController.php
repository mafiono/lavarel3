<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Bets\Betslip;


class BetslipController extends Controller
{
    public function placeBets(Betslip $betslip)
    {
        return $betslip->placeBets();
    }
}
