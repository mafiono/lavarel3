<?php

namespace App\Http\Controllers;

use App\Models\DailyBet;
use Carbon\Carbon;

class SuggestionsController extends Controller
{
    public function DailyBet()
    {
        return DailyBet::where('date', '=', Carbon::now()->startOfDay())
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
