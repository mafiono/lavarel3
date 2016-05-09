<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBetEvent extends Model
{
    protected $table = 'user_bet_events';

    protected $fillable = [
        'user_bet_id',
        'odd',
        'status',
        'event_name',
        'market_name',
        'game_name',
        'game_date',
        'api_event_id',
        'api_market_id',
        'api_game_id'
    ];

    protected $dates = [
        'gameDate'
    ];

    public function bet() {
        return $this->belongsTo('App\UserBet');
    }

}
