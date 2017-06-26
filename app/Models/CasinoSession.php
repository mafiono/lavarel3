<?php

namespace App\Models;

use App\Traits\CasinoDatabase;
use Illuminate\Database\Eloquent\Model;

class CasinoSession extends Model
{
    use CasinoDatabase;

    protected $table = 'sessions';

    public function rounds()
    {
        return $this->hasMany(CasinoRound::class, 'session_id');
    }

    public function game()
    {
        return $this->belongsTo(CasinoGame::class);
    }

}
