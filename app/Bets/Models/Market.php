<?php

namespace App\Bets\Models;

use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    protected $connection = 'odds';

    public function fixture()
    {
        return $this->belongsTo(Fixture::class);
    }

}