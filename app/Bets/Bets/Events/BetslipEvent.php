<?php

namespace App\Bets\Bets\Events;

use App\UserBetEvent;
use Carbon\Carbon;

class BetslipEvent extends UserBetEvent
{
    public function __construct($event)
    {
        $this->odd = $event['odd'];
        $this->status = 'waiting_result';
        $this->event_name = $event['eventName'];
        $this->market_name = $event['marketName'];
        $this->game_name = $event['gameName'];
        $this->api_event_id = $event['eventId'];
        $this->api_market_id = $event['marketId'];
        $this->api_game_id = $event['gameId'];
        $this->game_date = Carbon::createFromTimestamp($event['gameDate']);
    }
}
