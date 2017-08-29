<?php

namespace App\Models;

use App\Traits\Favoritable;
use App\Traits\CasinoDatabase;
use Illuminate\Database\Eloquent\Model;


class CasinoGame extends Model
{
    use CasinoDatabase;
    use Favoritable;

    protected $table = "games";

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public $incrementing = false;
}