<?php

namespace App;

use App\Bets\Bet;
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
        'odd',
        'amount',
        'currency',
        'status',
        'user_session_id',
    ];


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
        return $this->hasOne('App\UserBetStatus', 'user_bet_id', 'id')->where('status_id', $this->status);
    }

    /**
     * @return mixed
     */
    public function firstStatus()
    {
        return $this->hasOne('App\UserBetStatus', 'user_bet_id', 'id')->where('status_id', 'waiting_result');
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
        return $this->hasMany('App\UserBetTransactions');
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

    /**
     * @param Bet $bet
     * @return UserBet
     * @throws \Exception
     */
    public static function createBet(Bet $bet)
    {
        $newBet = new static;
        $newBet->user_id = $bet->getUser()->id;
        $newBet->api_bet_type = $bet->getApiType();
        $newBet->api_bet_id = $bet->getApiId();
        $newBet->api_transaction_id = $bet->getApiTransactionId();
        $newBet->odd = $bet->getOdd();
        $newBet->amount = $bet->getAmount();
        $newBet->amount_taxed = $bet->getAmount() * GlobalSettings::getTax();
        $newBet->currency = 'eur';
        $newBet->status = 'waiting_result';
        $newBet->user_session_id = UserSession::getSessionId();
        $newBet->save();

        UserBetStatus::setBetStatus($newBet);

        return $newBet;
    }

    public function setWinResult()
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
    }

    public function cancel()
    {
        $this->status = 'cancelled';
        $this->save();

        UserBetStatus::setBetStatus($this);
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
