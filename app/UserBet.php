<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

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
    * Relation with User
    *
    */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /**
    * Relation with BetStatus
    *
    */
    public function status()
    {
        return $this->hasMany('App\UserBetStatus', 'user_bet_id', 'id');
    }

    public function betslip()
    {
        return $this->belongsTo('App\UserBetslips');
    }

    public function statuses()
    {
        return $this->hasMany('App\UserBetStatuses');
    }

    public function transactions()
    {
        return $this->hasMany('App\UserBetTransactions');
    }

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
            "type" => "bet",
            "description" => "Nyx bet."
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
            "type" => "result",
            "description" => "Nyx result"
        ])))
            DB::rollback();
        else {
            DB::commit();
            if ($info !== null)
                $info = compact("userBet", "betStatus", "userBalance", "transaction");
        }
        return $userBet;
    }
}
