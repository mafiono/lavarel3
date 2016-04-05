<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class BonusTargets extends Model
{
    protected $fillable = [
        'bonus_id',
        'target_id'
    ];
}