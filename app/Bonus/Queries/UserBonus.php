<?php
namespace App\Bonus\Queries;

use App\Bonus;
use App\User;
use Illuminate\Database\Eloquent\Model;

class UserBonus extends Model
{
    protected $table = 'user_bonus';

    protected $fillable = [
        'user_id',
        'bonus_id',
        'bonus_head_id',
        'deadline_date',
        'active'
    ];
    protected $dates = ['deadline_date'];

    public function user()
    {
        return $this->belongsOne(User::class);
    }

    public function bonus()
    {
        return $this->hasOne(Bonus::class);
    }
}
