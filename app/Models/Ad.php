<?php

namespace App\Models;

use App\Traits\MainDatabase;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use MainDatabase;
    protected $table = "ads";
}