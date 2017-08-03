<?php

namespace App\Bets\Models;

use App\Traits\OddsDatabase;
use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
    use OddsDatabase;
    protected $table = "fixtures";

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }
}
