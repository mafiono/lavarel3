<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Bonus extends Model {
    protected $table = 'bonus';

    protected $fillable = [
        'bonus_type_id',
        'title',
        'description',
        'value',
        'value_type',
        'apply',
        'apply_deposit_methods',
        'destiny',
        'destiny_operation',
        'destiny_value',
        'target',
        'min_deposit',
        'max_deposit',
        'min_odd',
        'rollover_amount',
        'available_until',
        'deadline'
    ];

    public function bonusType() {
        return $this->hasOne('App\BonusTypes','id','bonus_type_id');
    }

}