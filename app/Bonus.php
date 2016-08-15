<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Bonus extends Model
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

    public function scopeAvailable($query, $user)
    {
        return $query->currents()
            ->availableBetweenNow()
            ->unUsed()
            ->userTargeted($user)
            ->firstDeposit($user);
    }

    public function scopeCurrents($query)
    {
        return $query->where('current','1');
    }

    public function scopeAvailableBetweenNow($query)
    {
        return $query->whereDate('bonus.available_from', '<=', Carbon::now()->format('Y-m-d'))
            ->whereDate('bonus.available_until', '>=', Carbon::now()->format('Y-m-d'));
    }

    public function scopeUnUsed($query)
    {
        return $query->whereNotExists(function ($query)
        {
            $query->select(DB::raw(1))
                ->from('user_bonus')
                ->where('user_bonus.bonus_head_id', 'bonus.head_id');
        });
    }

    public function scopeUserTargeted($query, $user)
    {
        return $query->where(function($query) use ($user)
        {
            $query->userGroupsTargeted($user)
                ->orUsernameTargeted($user);
        });
    }

    // check if target_id in bonus_targets
    public function scopeUserGroupsTargeted($query, $user)
    {
        $query->whereExists(function ($query) use ($user)
        {
            $query->select(DB::raw(1))
                ->from('bonus_targets')
                ->whereRaw('bonus_targets.bonus_id = bonus.id')
                ->where(function ($query) use ($user) {
                    $query->where('target_id', '=', $user->rating_risk)
                        ->orWhere('target_id', '=', $user->rating_group)
                        ->orWhere('target_id', '=', $user->rating_type)
                        ->orWhere('target_id', '=', $user->rating_class);
                });
        });
    }

    public function scopeOrUsernameTargeted($query, $user) {
        return $query->orWhere(function ($query) use ($user)
        {
            $query->userNameTargeted($user);
        });
    }

    public function scopeUsernameTargeted($query, $user)
    {
        return $query->whereExists(function ($query) use ($user)
        {
            $query->select(DB::raw(1))
                ->from('bonus_username_targets')
                ->whereRaw('bonus_username_targets.bonus_id = bonus.id')
                ->where('bonus_username_targets.username', '=', $user->username);
        });
    }

    public function scopeFirstDeposit($query, $user)
    {
        return $query->where(function ($query) use ($user)
        {
            $query->where('bonus_type_id', '=', 'first_deposit')
                ->whereExists(function ($query) use ($user)
                {
                    $query->select(DB::raw(1))
                        ->from('user_transactions')
                        ->where('user_transactions.status_id', 'processed')
                        ->where('user_transactions.user_id', $user->id);
                });
        });
    }

    public function bonusType()
    {
        return $this->belongsTo(BonusTypes::class);
    }

    public function userBonus()
    {
        return $this->hasMany(UserBonus::class, 'bonus_id');
    }
}