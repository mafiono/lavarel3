<?php
namespace App;

use Illuminate\Database\Eloquent\Model;


class UserBonus extends Model {
    protected $table = 'user_bonus';
    protected $fillable = [
        'user_id',
        'bonus_id',
        'active'
    ];

    public function users() {
        return $this->belongsToMany('App\User');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function bonus() {
        return $this->belongsTo('App\Bonus');
    }
}