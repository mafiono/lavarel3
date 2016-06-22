<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Error extends Model
{
    protected $fillable = [
        'error',
        'msg',
        'type',
        'status',
        'solution',
        'staff_id'
    ];
}
