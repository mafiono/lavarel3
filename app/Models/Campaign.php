<?php

namespace App\Models;

use App\Traits\MainDatabase;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use MainDatabase;
    protected $table = "campaign";
}