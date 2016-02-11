<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRollbacksNYX extends Model {
    protected $table = 'user_rollbacks_nyx';
    protected $fillable =  [
        "user_bet_id",
        "accountid",
        "device",
        "gamemodel",
        "gamesessionid",
        "gametype",
        "gpgameid",
        "gpid",
        "nogsgameid",
        "product",
        "rollbackamount",
        "roundid",
        "transactionid"
    ];

    /**
     * Relation with UserBets
     */
    public function user_bet() {
        return $this->belongsTo('App\UserBets', 'user_bet_id', 'id');
    }

}
