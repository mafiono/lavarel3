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



}
