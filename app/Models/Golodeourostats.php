<?php

namespace App\Models;

use App\Bets\Models\Fixture;
use App\Traits\MainDatabase;
use Illuminate\Database\Eloquent\Model;

class GoloDeOuroStats extends Model
{
    use MainDatabase;
    protected $table = 'cp_fixture_summarizeds';


    public function golodeouro()
    {
        return $this->belongsTo(Golodeouro::class, 'cp_fixture_id');
    }
}


