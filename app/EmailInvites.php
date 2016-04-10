<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailInvites extends Model
{
    protected $fillable = [
        'user_id',
        'email'
    ];
}
