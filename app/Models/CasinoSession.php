<?php

namespace App\Models;

use App\Traits\CasinoDatabase;
use Illuminate\Database\Eloquent\Model;

class CasinoSession extends Model
{
    use CasinoDatabase;

    protected $table = 'sessions';

    protected $fillable = [
        'provider',
        'sessionid',
        'user_id',
        'token_id',
        'game_id',
        'operator',
        'sessionstatus',
        'time_start',
        'time_end',
        'balance_start',
        'balance_end',
        'stake_sum',
        'win_sum',
        'jp_contribution_sum',
        'jp_win_sum',
    ];
}
