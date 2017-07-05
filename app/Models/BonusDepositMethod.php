<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonusDepositMethod extends Model
{
    protected $fillable = [
        'bonus_id',
        'deposit_method_id'
    ];
}
