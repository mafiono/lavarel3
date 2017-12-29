<?php

namespace App\Models;

use App\Traits\MainDatabase;
use Illuminate\Database\Eloquent\Model;

class GolodeouroMarket extends Model
{
    use MainDatabase;
    protected $table = 'golodeouro_markets';

}
