<?php

namespace App\Models;

use App\Traits\MainDatabase;
use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    use MainDatabase;
    protected $table = "affiliates";
}