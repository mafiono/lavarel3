<?php

namespace App\Http\Controllers;

use App\Models\DailyBet;
use Carbon\Carbon;

class SuggestionsController extends Controller
{
    public function DailyBet()
    {
        return DailyBet::where('date', '=', Carbon::now()->startOfDay())
            ->where('active',1)
            ->with('selections')
            ->get()
            ->map(function($bet) {
                return [
                    'description' => $bet->description,
                    'selections' => $bet->selections->pluck('selection_id'),
                ];
            })->first();
    }
}
