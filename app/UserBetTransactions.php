<?php

namespace App;

use App\Betslip\Bet;
use Illuminate\Database\Eloquent\Model;


class UserBetTransactions extends Model {
    protected $table = 'user_bet_transactions';


    /**
     * Relation with UserBets
     */
    public function user_bet() {
        return $this->belongsTo('App\UserBets', 'user_bet_id', 'id');
    }

    /**
     * Finds UserBetTransaction by API transaction id
     * @param $tid
     * @return mixed
     */
    public static function findByApiTransaction($tid) {
        return self::where("api_transaction_id", "=", $tid)->first();
    }

    public static function createTransaction(Bet $bet, $operation, $userBetStatusId, array $balances = []) {
        $trans = new self;
        $trans->user_bet_id = $bet->getUser()->id;
        $trans->api_transaction_id = $bet->getApiTransactionId();
        $trans->user_bet_status_id = $userBetStatusId;
        $trans->operation = $operation;
        $trans->initial_balance = $balances['initial_balance'];
        $trans->final_balance = $balances['final_balance'];
        $trans->initial_bonus = $balances['initial_bonus'];
        $trans->final_bonus = $balances['final_bonus'];
        $trans->save();
        return $trans;
    }

}
