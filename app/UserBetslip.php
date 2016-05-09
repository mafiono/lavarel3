<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBetslip extends Model
{
    public function user() {
        $this->belongsTo('App\Users');
    }

    public function bets() {
        $this->hasMany('App\UserBets');
    }
}
