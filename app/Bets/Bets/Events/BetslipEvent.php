<?php

namespace App\Bets\Bets\Events;

use App\UserBetEvent;
use Carbon\Carbon;

class BetslipEvent extends UserBetEvent
{
    public static function make($event)
    {
        $newEvent = new static;

        $newEvent->odd = $event['odds'];
        $newEvent->status = 'waiting_result';
        $newEvent->event_name = $event['name'];
        $newEvent->market_name = $event['marketName'];
        $newEvent->game_name = $event['gameName'];
        $newEvent->api_event_id = $event['id'];
        $newEvent->api_market_id = $event['marketId'];
        $newEvent->api_game_id = $event['gameId'];
        $newEvent->game_date = Carbon::parse($event['gameDate']);

        return $newEvent;
    }
}
