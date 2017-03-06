<?php

namespace App\Models;

use App\Traits\CasinoDatabase;
use Illuminate\Database\Eloquent\Model;

class CasinoRound extends Model
{
    use CasinoDatabase;

    protected $table = 'rounds';
}