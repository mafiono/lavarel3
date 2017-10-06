<?php

namespace App;

use App\Models\CasinoGame;
use App\Traits\CasinoDatabase;
use Illuminate\Database\Eloquent\Model;

class CasinoTransaction extends Model {
    use CasinoDatabase;
    protected $table = 'transactions';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(CasinoGame::class);
    }
}
