<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBetNYX extends Model {
    protected $table = 'user_bet_nyx';
    protected $fillable =  [
        "user_bet_id",
        "accountid",
        "device",
        "gamemodel",
        "gamesessionid",
        "gamestatus",
        "gametype",
        "gpgameid",
        "gpid",
        "nogsgameid",
        "product",
        "result",
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
