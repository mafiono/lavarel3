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

    public static function scopeAvailableBonuses($query, $user) {
        return $query->where('current','1')
            ->whereDate('available_from', '<=', Carbon::now()->format('Y-m-d'))// check if is in the available date interval
            ->whereDate('available_until', '>=', Carbon::now()->format('Y-m-d'))
            ->leftJoin('bonus_types', 'bonus.bonus_type_id', '=', 'bonus_types.id')
            ->select ('*', 'bonus_types.name AS bonus_type', 'bonus.id AS id')
            ->leftJoin('user_bonus', function ($join) use ($user) {
                $join->on('user_bonus.bonus_id', '=', 'bonus.id')
                    ->where('user_bonus.user_id', '=', $user->id);
            })
            ->where( function($query) use ($user) {
                $query->whereExists(function ($query) use ($user) { // check if target_id in bonus_targets
                    $query->select('target_id')
                        ->from('bonus_targets')
                        ->whereRaw('bonus_targets.bonus_id = bonus.id')
                        ->where(function ($query) use ($user) {
                            $query->where('target_id', '=', $user->rating_risk)
                                ->orWhere('target_id', '=', $user->rating_group)
                                ->orWhere('target_id', '=', $user->rating_type)
                                ->orWhere('target_id', '=', $user->rating_class);
                        });
                    })
                    ->orWhereExists(function ($query) use ($user) { // check if username in bonus_username_targets
                        $query->select('username')
                            ->from('bonus_username_targets')
                            ->whereRaw('bonus_username_targets.bonus_id = bonus.id')
                            ->where(function ($query) use ($user) {
                                $query->where('username', '=', $user->username);
                            });
                    });
            })
            ->whereNull('user_bonus.bonus_id'); // check if is not in not used or consumed
    }

    /**
     * @param $user
     * @return mixed
     */
    public static function getAvailableBonuses($user) {
        return self::availableBonuses($user)
            ->get();
    }

    /**
     * @param $user
     * @param $bonus_id
     * @return bool
     */
    public static function isBonusAvailable($user, $bonus_id) {
        return self::availableBonuses($user)
            ->where('bonus.id', $bonus_id)
            ->count() === 1;
    }
}