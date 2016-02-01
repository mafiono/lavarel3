<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBet extends Model
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
    * Relation with BetStatus
    *
    */
    public function status()
    {
        return $this->belongsTo('App\UserBetStatus', 'user_bet_id', 'id');
    }    

}
