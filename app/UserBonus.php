<?php
namespace App;

use App\Traits\MainDatabase;
use Illuminate\Database\Eloquent\Model;

class UserBonus extends Model
{
    use MainDatabase;
    protected $table = 'user_bonus';

    protected $fillable = [
        'user_id',
        'user_session_id',
        'bonus_id',
        'bonus_head_id',
        'deadline_date',
        'bonus_value',
        'active',
        'deposited',
        'rollover_amount',
    ];

    protected $dates = ['deadline_date'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function bonus()
    {
        return $this->belongsTo(Bonus::class);
    }

    public function userBets()
    {
        return $this->hasMany(UserBet::class);
    }

    public function scopeFromUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeCountActive($query)
    {
        return $query->count();
    }

    public function scopeActive($query)
    {
        return $query->where('active', '1');
    }

    public function scopeActiveFromUser($query, $userId, $with = [])
    {
        return $query->active()
            ->fromUser($userId)
            ->with($with);
    }

    public function scopeConsumed($query)
    {
        return $query->where('active', '0');
    }

    public function scopeOrigin($query, $origin)
    {
        return $query->whereHas('bonus', function ($query) use ($origin) {
           $query->whereBonusOriginId($origin);
        });
    }

    public function addWageredBonus($amount)
    {
        $this->freshLockForUpdate();

        $this->bonus_wagered += $amount;

        $this->save();
    }

    public function subtractWageredBonus($amount)
    {
        $this->freshLockForUpdate();

        $this->bonus_wagered -= $amount;

        $this->save();
    }

    //TODO: make trait
    private function freshLockForUpdate()
    {
        $this->forceFill((static::where('id', $this->id)->lockForUpdate()->first()->attributesToArray()));
    }
}
