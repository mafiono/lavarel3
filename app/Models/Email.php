<?php

namespace App\Models;

use App\Traits\MainDatabase;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use MainDatabase;
    protected $table = "emails";
}