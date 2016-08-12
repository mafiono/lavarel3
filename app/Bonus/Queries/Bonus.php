<?php
namespace App\Bonus\Queries;


use Illuminate\Database\Eloquent\Model;

class UserBonus extends Model
{

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


}