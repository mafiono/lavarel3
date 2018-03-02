<?php

namespace App\Http\Controllers\Casino;

use App\Http\Controllers\Controller;
use App\Models\CasinoRound;
use Carbon\Carbon;

class OpenRoundsController extends Controller
{
    public function index()
    {
        return CasinoRound::whereUserId(auth()->user()->id)
            ->whereRoundstatus('active')
            ->where('created_at', '<', Carbon::now()->subMinutes(5))
            ->groupBy('game_id')
            ->orderBy('created_at', 'DESC')
            ->with('game')
            ->get()
            ->map(function ($round) {
                return $round->game;
            });
    }
}
