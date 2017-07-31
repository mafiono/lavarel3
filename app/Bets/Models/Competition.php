<?php

namespace App\Bets\Models;

use App\Traits\OddsDatabase;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use OddsDatabase;
    protected $table = "competitions";
}
