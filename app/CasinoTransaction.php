<?php

namespace App;

use App\Traits\CasinoDatabase;
use Illuminate\Database\Eloquent\Model;

class CasinoTransaction extends Model {
    use CasinoDatabase;
    protected $table = 'transactions';
}
