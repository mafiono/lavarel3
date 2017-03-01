<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


/**
 * Class UserBet
 * @package App
 */
class UserBet extends Model
{
    protected $table = 'user_bets';

    protected $fillable = [
        'user_id',
        'api_bet_id',
        'api_bet_type',
        'api_transaction_id',
        'type',
        'odd',
        'amount',
        'currency',
        'status',
        'user_session_id',
        'user_bonus_id',
    ];

    public function scopeFromUser($query, $id)
    {
        return $query->where('user_id', $id);
    }

    public function scopeWaitingResult($query)
    {
        return $query->where('status', 'waiting_result');
    }

    public function scopePastGames($query)
    {
        return $query;
    }

    public function scopeWithValidOdd($query)
    {
        return $query->where('odd', '>', 1);
    }

    public function scopeFromBonus($query, $bonusId)
    {
        return $query->where('user_bonus_id', $bonusId);
    }

    public static function fetchUnresolvedBets()
    {
        return static::pastGames()->waitingResult()->withValidOdd()->get();
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function statuses()
    {
        return $this->hasMany('App\UserBetStatus', 'user_bet_id', 'id');
    }

    public function currentStatus()
    {
        return $this->hasOne('App\UserBetStatus', 'user_bet_id', 'id')->where('current', 1);
    }

    public function waitingResultStatus()
    {
        return $this->hasOne('App\UserBetStatus', 'user_bet_id', 'id')->where('status', 'waiting_result');
    }

    public function events()
    {
        return $this->hasMany('App\UserBetEvent', 'user_bet_id', 'id');
    }

    public function lastEvent()
    {
        $last = null;

        foreach ($this->events as $event) {
            if (!$last || $last->game_date < $event->game_date) {
                $last = $event;
            }
        }

        return $last;
    }

    public function betslip()
    {
        return $this->belongsTo(UserBetslip::class, 'user_betslip_id');
    }

    public function transactions()
    {
        return $this->hasMany(UserBetTransaction::class, 'user_bet_id');
    }

    public function fetchStateTransaction($state)
    {
        return $this->statuses()->where('state', $state)->transaction()->first();
    }

    public static function findByApi($type, $id)
    {
        return self::where('api_bet_type', $type)->where('api_bet_id', $id)->first();
    }

    public function scopeUnresolvedBets($query)
    {
        return $query->where('status', 'waiting_result');
    }

    public static function dailyAmount($user_id)
    {
        return self::where('user_id', $user_id)
            ->where('created_at', '>=', Carbon::now()->startOfDay())
            ->sum('amount');
    }

    public static function weeklyAmount($user_id)
    {
        return self::where('user_id', $user_id)
            ->where('created_at', '>=', Carbon::now()->startOfWeek())
            ->sum('amount');
    }

    public static function monthlyAmount($user_id)
    {
        return self::where('user_id', $user_id)
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->sum('amount');
    }

}
