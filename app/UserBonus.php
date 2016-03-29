<?php
namespace App;

use Illuminate\Database\Eloquent\Model;


class UserBonus extends Model {
    protected $table = 'user_bonus';
    protected $fillable = [
        'user_id',
        'bonus_id'
    ];

    public function setUpdatedAt($value) {
    }

    public function bonus() {
        return $this->belongsToMany('App\Bonus');
    }
}