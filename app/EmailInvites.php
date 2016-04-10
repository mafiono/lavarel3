<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailInvites extends Model
{
    protected $primaryKey = [
        'user_id',
        'email'
    ];

    protected $fillable = [
        'user_id',
        'email'
    ];
}
