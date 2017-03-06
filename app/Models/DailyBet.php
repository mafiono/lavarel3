<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyBet extends Model
{
    protected $table = 'daily_bet';

    public function selections()
    {
        return $this->hasMany(SelectionsDaily::class, 'dailybet_id');
    }
}
