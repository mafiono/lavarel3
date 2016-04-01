<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBetBC extends Model {
    protected $table = 'user_bet_bc';
    protected $fillable =  [
        "user_bet_id",
        "date_time",
        "type",
        "amount",
        "currency",
        "sysbet",
        "bet_coeficient"
    ];

    /**
     * Relation with UserBets
     */
    public function user_bet() {
        return $this->belongsTo('App\UserBets', 'user_bet_id', 'id');
    }

}
