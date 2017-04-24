<?php

namespace App\Bets\Models;

use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
    protected $connection = 'odds';

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }
}
