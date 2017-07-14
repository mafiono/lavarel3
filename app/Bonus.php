<?php

namespace App;

use App\Models\BonusDepositMethod;
use App\Traits\MainDatabase;
use DB;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Bonus extends Model
{
    use MainDatabase;
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

    public function bonusType()
    {
        return $this->belongsTo(BonusTypes::class);
    }

    public function userBonus()
    {
        return $this->hasMany(UserBonus::class, 'bonus_id');
    }

    public function depositMethods()
    {
        return $this->hasMany(BonusDepositMethod::class);
    }

    public function scopeAvailableBonuses($query, $user)
    {
        return $query->currents()
            ->availableBetweenNow()
            ->unUsed($user)
            ->where(function ($query) use ($user) {
                $query->where(function ($query) use ($user) {
                    $query->firstDeposit($user);
                })->orWhere(function ($query) use ($user) {
                    $query->firstBet($user);
                });
            });
    }

    public function scopeCurrents($query)
    {
        return $query->where('current', '1');
    }

    public function scopeAvailableBetweenNow($query)
    {
        return $query->whereDate('bonus.available_from', '<=', Carbon::now()->format('Y-m-d'))
            ->whereDate('bonus.available_until', '>=', Carbon::now()->format('Y-m-d'));
    }

    public function scopeUnUsed($query, $user)
    {
        return $query->whereNotExists(function ($query) use ($user) {
            $query->select(DB::raw(1))
                ->from('user_bonus')
                ->whereRaw('user_bonus.user_id = ' . $user->id)
                ->whereRaw('user_bonus.bonus_head_id = bonus.head_id');
        });
    }

    public function scopeUserTargeted($query, $user)
    {
        return $query->where(function ($query) use ($user) {
            $query->userGroupsTargeted($user)
                ->orUsernameTargeted($user);
        });
    }

    // check if target_id in bonus_targets
    public function scopeUserGroupsTargeted($query, $user)
    {
        $query->whereExists(function ($query) use ($user) {
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

    public function scopeUsernameTargeted($query, $user)
    {
        return $query->whereExists(function ($query) use ($user) {
            $query->select(DB::raw(1))
                ->from('bonus_username_targets')
                ->whereRaw('bonus_username_targets.bonus_id = bonus.id')
                ->where('bonus_username_targets.username', '=', $user->username);
        });
    }

    public function scopeTargetDepositMethods($query, $userId) {
        return $query->whereExists(function ($query) use ($userId) {
            $query->select(DB::raw(1))
                ->from('bonus_deposit_methods')
                ->join(DB::raw("(SELECT origin,created_at FROM user_transactions " .
                    "WHERE user_transactions.status_id='processed' " .
                    "AND user_transactions.origin IN ('bank_transfer','cc','mb','meo_wallet','paypal') " .
                    "AND user_transactions.user_id='${userId}' " .
                    "ORDER BY id DESC LIMIT 1) as ut"), "ut.origin", '=', 'bonus_deposit_methods.deposit_method_id'
                )->whereRaw("bonus_deposit_methods.bonus_id = bonus.id ")
                ->whereRaw("bonus_deposit_methods.deposit_method_id = ut.origin")
                ->whereRaw("ut.created_at > bonus.available_from");
        });
    }

    public function scopeFirstDeposit($query, $user)
    {
        return $query->whereBonusTypeId('first_deposit')
            ->transactionsCount($user->id, 1)
            ->lastUserDepositAboveMinDeposit($user->id)
            ->targetDepositMethods($user->id)
            ->userUsedNoBonusSinceLastDeposit($user->id);
    }

    public function scopeFirstBet($query, $user)
    {
        return $query->whereBonusTypeId('first_bet')
            ->transactionsCount($user->id, 1)
            ->lastUserDepositAboveMinDeposit($user->id)
            ->userLostFirstBetSinceLastDeposit($user->id)
            ->targetDepositMethods($user->id)
            ->userUsedNoBonusSinceLastDeposit($user->id);
    }

    public function scopeHasBonus($query, $bonusId)
    {
        return $query->where('id', $bonusId);
    }

    public function scopeTransactionsCount($query, $userId, $count)
    {
        return  $query->whereRaw(
            "(SELECT COUNT(*) FROM user_transactions ".
            "WHERE status_id='processed' AND user_id='$userId' ".
            "AND origin IN ('bank_transfer','cc','mb','meo_wallet','paypal')) = $count"
        );
    }

    public function scopeHasNoBetsFromUser($query, $userId)
    {
        return $query->whereNotExists(function ($query) use ($userId) {
            $query->select(DB::raw(1))
                ->from('user_bets')
                ->whereRaw('user_bets.user_id = ' . $userId);
        });
    }

    public function scopeUserLostFirstBetSinceLastDeposit($query, $userId)
    {
        return $query->whereExists(function ($query) use ($userId) {
            return $query->select(DB::raw(1))
                ->from(DB::raw(
                    "(SELECT amount,status,type,odd FROM user_bets" .
                    " WHERE user_id = $userId AND status != 'returned'" .
                    " AND created_at >= " . static::latestDepositCreatedDateRawQuery($userId) .
                    " ORDER BY id ASC LIMIT 1 " .
                    ") as first_bet"
                ))->whereRaw("first_bet.status = 'lost'")
                    ->whereRaw("first_bet.type = 'multi'")
                    ->whereRaw("first_bet.odd >= bonus.min_odd");
        });
    }

    public function scopeLastUserDepositAboveMinDeposit($query, $userId)
    {
        return $query->whereRaw('min_deposit <= ' . static::latestUserDepositRawQuery($userId));
    }

    public function scopeLatestUserDeposit($query, $userId)
    {
        return $query->select(
            '(' .
                'SELECT debit FROM user_transactions ' .
                'WHERE user_transactions.status_id=\'processed\' ' .
                "AND user_transactions.origin IN ('bank_transfer','cc','mb','meo_wallet','paypal') " .
                'AND user_transactions.created_at > bonus.available_from ' .
                'AND user_transactions.user_id=\'' . $userId . '\' ' .
                'ORDER BY id DESC LIMIT 1' .
            ')'
        );
    }

    public function scopeUserHasNoBetsAfterLastTransaction($query, $userId)
    {
        return $query->whereNotExists(function ($query) use ($userId) {
            $query->select(DB::raw(1))
                ->from('user_bets')
                ->whereRaw('user_bets.user_id = ' . $userId)
                ->whereRaw('user_bets.created_at >= ' . static::latestDepositCreatedDateRawQuery($userId));
        });
    }

    public static function latestUserDepositRawQuery($userId)
    {
        return '(' .
            'SELECT debit FROM user_transactions ' .
            'WHERE user_transactions.status_id=\'processed\' ' .
            'AND origin != \'sport_bonus\' ' .
            'AND user_transactions.created_at > bonus.available_from ' .
            'AND user_transactions.user_id=\'' . $userId . '\' ' .
            'ORDER BY id DESC LIMIT 1' .
        ')';
    }

    public static function latestDepositCreatedDateRawQuery($userId)
    {
        return '(' .
            'SELECT created_at FROM user_transactions ' .
            'WHERE status_id=\'processed\' ' .
            'AND user_id=\''. $userId .'\' ' .
            "AND user_transactions.origin IN ('bank_transfer','cc','mb','meo_wallet','paypal') " .
            'ORDER BY id DESC LIMIT 1' .
        ')';
    }

    public function scopeUserUsedNoBonusSinceLastDeposit($query, $userId)
    {
        return $query->whereNotExists(function ($query) use ($userId) {
            $query->select(DB::raw(1))
                ->from('user_bonus')
                ->whereRaw('user_bonus.user_id = ' . $userId)
                ->whereRaw('user_bonus.created_at >= ' . static::latestUserDepositRawQuery($userId));
        });
    }
}
