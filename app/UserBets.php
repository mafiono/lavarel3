<?php

namespace App;

use App\Betslip\Bet;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserBets extends Model
{
    protected $table = 'user_bets';

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
    public function status()
    {
        return $this->hasMany('App\UserBetStatus', 'user_bet_id', 'id');
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
    public function statuses()
    {
        return $this->hasMany('App\UserBetStatuses');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany('App\UserBetTransactions');
    }

    /**
     * @param Bet $bet
     * @return UserBets
     * @throws \Exception
     */
    public static function createBet(Bet $bet) {
        $user = $bet->getUser();

        $newBet = new UserBets;
        $newBet->user_id = $user->id;
        $newBet->api_bet_type = $bet->getApiBetType();
        $newBet->api_transaction_id = $bet->getApiTransactionId();
        $newBet->amount = $bet->getAmount();
        $newBet->currency = 'eur';
        $newBet->status = 'waiting_result';
        $newBet->user_session_id = UserSession::getSessionId();
        $newBet->save();

        return $newBet;
    }

    public static function dailyAmount($user)
    {
        return self::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->startOfDay())
            ->sum('amount');
    }


    public static function weeklyAmount($user)
    {
        return self::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->startOfWeek())
            ->sum('amount');
    }

    public static function monthlyAmount($user)
    {
        return self::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->sum('amount');
    }
}
