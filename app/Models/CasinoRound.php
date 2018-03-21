<?php

namespace App\Models;

use App\Traits\CasinoDatabase;
use Illuminate\Database\Eloquent\Model;

class CasinoRound extends Model
{
    use CasinoDatabase;

    protected $table = 'rounds';

    protected $fillable = [
        'user_id',
        'session_id',
        'game_id',
        'roundstatus',
        'user_bonus_id',
    ];

    public function transactions()
    {
        return $this->hasMany(CasinoTransaction::class, 'round_id');
    }

    public function game()
    {
        return $this->belongsTo(CasinoGame::class);
    }
}
