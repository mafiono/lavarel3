<?php

namespace App\Bets\Models;

use App\Traits\OddsDatabase;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    use OddsDatabase;

    public function fixture()
    {
        return $this->belongsTo(Fixture::class);
    }

}