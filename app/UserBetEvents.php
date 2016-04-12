<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBetEvents extends Model
{
    public function userBet() {
        return $this->belongsTo('App\UserBet');
    }
}
