<?php

namespace App;

use App\Betslip\Bet;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class UserBet extends Model
{
    protected $table = 'user_bets';
    protected $fillable = [
        "user_id",
        "api_bet_id",
        "api_bet_type",
        "api_transaction_id",
        "amount",
        "currency",
        "user_session_id",
        "status",
    ];

    /**
     * Create a NYX bet
     * @param $bet
     * @param $info
     * @return UserBet
     */
    public static function createNyxBet($bet, &$info) {
        DB::beginTransaction();
        if (!($userBet = UserBet::create($bet)))
            DB::rollback();
        elseif (!(new UserBetStatus)->setStatus('waiting_result', $userBet->id, $bet['user_session_id']))
            DB::rollBack();
        elseif (!$userBet->user->balance->subtractAvailableBalance($userBet->amount))
            DB::rollback();
        elseif (!($transaction = UserBetTransactions::create([
            "user_bet_id" => $userBet->id,
            "api_transaction_id" => $userBet->api_transaction_id,
            "operation" => "withdrawal",
            "amount" => $userBet->amount,
        ])))
            DB::rollback();
        else {
            DB::commit();
            if ($info !== null)
                $info = compact("userBet", "betStatus", "userBalance", "transaction");
        }
        return $userBet;
    }

    /**
     * Update a NyxBet
     * @param UserBet $userBet
     * @param $amount
     * @param $info
     * @return UserBet
     */
    public static function updateNyxBet($userBet, $amount, &$info) {
        DB::beginTransaction();
        if (!$userBet->save() || !(new UserBetStatus)->setStatus($userBet->status, $userBet->id, $userBet->user_session_id))
            DB::rollback();
        elseif (!$userBet->user->balance->addAvailableBalance($amount))
            DB::rollback();
        elseif (!($transaction = UserBetTransactions::create([
            "user_bet_id" => $userBet->id,
            "api_transaction_id" => $userBet->api_transaction_id,
            "operation" => "deposit",
            "amount" => $amount,
        ])))
            DB::rollback();
        else {
            DB::commit();
            if ($info !== null)
                $info = compact("userBet", "betStatus", "userBalance", "transaction");
        }
        return $userBet;
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
        $newBet->amount_taxed = $bet->getAmount() * GlobalSettings::getTax();
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
