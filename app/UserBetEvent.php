<?php

namespace App;

use App\Bets\Bets\Bet;
use App\Traits\MainDatabase;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserBetEvent extends Model
{
    use MainDatabase;
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
        return $this->belongsTo(Bet::class, 'user_bet_id', 'id');
    }

    public function scopeUnresolved($query)
    {
        $query->where('status', 'waiting_result');
    }

    public function scopePast($query, $minutes = 0)
    {
        $query->where('game_date', '<', Carbon::now()->tz('UTC')->subMinutes($minutes));
    }

}
