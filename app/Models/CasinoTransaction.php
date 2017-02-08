<?php

namespace App\Models;

use App\Traits\CasinoDatabase;
use Illuminate\Database\Eloquent\Model;

class CasinoTransaction extends Model
{
    use CasinoDatabase;

    protected $table = 'transactions';

    public function game()
    {
        return $this->belongsTo(CasinoGame::class);
    }

    public function round()
    {
        return $this->belongsTo(CasinoRound::class);
    }
}
