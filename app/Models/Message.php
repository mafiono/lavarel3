<?php

namespace App\Models;

use App\Traits\MainDatabase;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use MainDatabase;
    protected $table = 'messages';
}
