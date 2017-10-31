<?php

namespace App\Http\Controllers\Casino;

use App\CasinoTransaction;
use App\Http\Controllers\Controller;
use DB;

class CasinoRecentWinnersController extends Controller
{
    public function index()
    {
        return CasinoTransaction::select(DB::raw('user_id, round_id, game_id, SUM( amount + amount_bonus ) as totalAmount'))
            ->whereType('win')
            ->latest('round_id')
            ->groupBy('round_id')
            ->take(10)
            ->having('totalAmount', '>', 100)
            ->with(['game', 'user'])
            ->get()
            ->map(function ($win) {
                return [
                    'amount' => number_format($win->totalAmount, 0, '.', ' ') . '€',
                    'username' => '***' . substr($win->user->username, 3),
                ] + $win->game->toArray();
            });
    }
}
