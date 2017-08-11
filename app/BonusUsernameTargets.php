<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BonusUsernameTargets extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'bonus_id',
        'username',
    ];
}
