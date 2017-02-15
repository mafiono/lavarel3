<?php

namespace App\Models;

use App\Traits\CasinoDatabase;
use Illuminate\Database\Eloquent\Model;

class CasinoToken extends Model
{
    use CasinoDatabase;

    protected $table = 'tokens';

    protected $fillable = [
        'user_id',
        'user_session_id',
        'tokenid',
        'used',
    ];
}
