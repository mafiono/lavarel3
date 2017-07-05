<?php

namespace App\Models;

use App\Traits\MainDatabase;
use Illuminate\Database\Eloquent\Model;

class Adclick extends Model
{
    use MainDatabase;
    protected $table = "adclicks";
}