<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBetslip extends Model
{
    protected $fillable = ['user_id'];

    protected function setUpdatedAtAttribute($value) {}

    public function user()
    {
        $this->belongsTo('App\Users');
    }

    public function bets()
    {
        $this->hasMany('App\UserBets');
    }



}
