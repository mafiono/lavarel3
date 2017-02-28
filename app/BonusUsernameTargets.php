<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BonusUsernameTargets extends Model
{
    protected $fillable = [
        'bonus_id',
        'username',
    ];
}
