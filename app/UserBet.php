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
    /**
     * @var string
     */
    protected $table = 'user_bets';
    /**
     * @var array
     */
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
    ];

    public function scopeWaitingResult($query)
    {
        return $query->where('status', 'waiting_result');
    }

    public function scopePastGames($query)
    {
        return $query;
    }

    public function scopeWithValidOdd($query) {
        return $query->where('odd', '>', 1);
    }

    public static function fetchUnresolvedBets()
    {
        return static::query()->pastGames()->waitingResult()->withValidOdd()->get();
    }

    /**
     * Relation with User
     *
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function statuses()
    {
        return $this->hasMany('App\UserBetStatus', 'user_bet_id', 'id');
    }

    /**
     * @return mixed
     */
    public function currentStatus()
    {
        return $this->hasOne('App\UserBetStatus', 'user_bet_id', 'id')->where('current', 1);
    }

    //TODO: this needs to change
    public function waitingResultStatus()
    {
        return $this->hasOne('App\UserBetStatus', 'user_bet_id', 'id')->where('status', 'waiting_result');
    }
    public function events()
    {
        return $this->hasMany('App\UserBetEvent', 'user_bet_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function betslip()
    {
        return $this->belongsTo('App\UserBetslips');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany('App\UserBetTransaction');
    }

    /**
     * @param $state
     * @return mixed
     */
    public function fetchStateTransaction($state)
    {
        return $this->statuses()->where('state', $state)->transaction()->first();
    }

    /**
     * @param $type
     * @param $id
     * @return mixed
     */
    public static function findByApi($type, $id) {
        return self::where('api_bet_type', $type)->where('api_bet_id', $id)->first();
    }

    public function scopeUnresolvedBets($query)
    {
        return $query->where('status', 'waiting_result');
    }

    /**
     * @param Bet $bet
     * @return static
     * @throws \Exception
     */
    public function placeBet()
    {
        $this->amount_taxed = $this->amount * (1-GlobalSettings::getTax());
        $this->currency = 'eur';
        $this->status = 'waiting_result';
        $this->user_session_id = UserSession::getSessionId();
        $this->save();

        UserBetStatus::setBetStatus($this);

        foreach ($this->events as $event) {
            $event->user_bet_id = $this->id;
            $event->save();
        }

        return $this;
    }

    public function setWonResult()
    {
        $this->result = 'won';
        $this->result_amount = $this->amount_taxed*$this->odd;
        $this->status = 'won';
        $this->save();

        UserBetStatus::setBetStatus($this);

        return $this;
    }

    public function setLostResult()
    {
        $this->result = 'lost';
        $this->status = 'lost';
        $this->save();

        UserBetStatus::setBetStatus($this);

        return $this;
    }

    public function cancelBet()
    {
        $this->status = 'cancelled';
        $this->save();

        return $this;
    }

    /**
     * @param $user_id
     * @return float
     */
    public static function dailyAmount($user_id)
    {
        return (float) self::where('user_id', $user_id)
            ->where('created_at', '>=', Carbon::now()->startOfDay())
            ->sum('amount');
    }

    /**
     * @param $user_id
     * @return float
     */
    public static function weeklyAmount($user_id)
    {
        return (float) self::where('user_id', $user_id)
            ->where('created_at', '>=', Carbon::now()->startOfWeek())
            ->sum('amount');
    }

    /**
     * @param $user_id
     * @return float
     */
    public static function monthlyAmount($user_id)
    {
        return (float) self::where('user_id', $user_id)
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->sum('amount');
    }

}
