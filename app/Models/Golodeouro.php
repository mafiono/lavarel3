<?php

namespace App\Models;

use App\Bets\Models\Fixture;
use App\Traits\MainDatabase;
use Illuminate\Database\Eloquent\Model;

class Golodeouro extends Model
{
    use MainDatabase;
    protected $table = 'golodeouro';


    public function fixture()
    {
        return $this->belongsTo(Fixture::class, 'fixture_id');
    }
}


