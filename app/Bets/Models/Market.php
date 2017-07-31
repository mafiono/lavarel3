<?php

namespace App\Bets\Models;

use App\Traits\OddsDatabase;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    use OddsDatabase;
    protected $table = "markets";

    public function fixture()
    {
        return $this->belongsTo(Fixture::class);
    }

}