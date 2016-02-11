<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class UserBetTransactions extends Model {
    protected $table = 'user_bet_transactions';
    protected $fillable =  [
        "user_bet_id",
        "api_transaction_id",
        "operation",
        "amount",
        "type",
        "description",
    ];

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
}
