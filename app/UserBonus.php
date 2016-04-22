<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Exception;
use Carbon\Carbon;

class UserBonus extends Model {
    protected $table = 'user_bonus';
    protected $fillable = [
        'user_id',
        'bonus_id',
        'bonus_head_id',
        'deadline_date',
        'active'
    ];

    public function users() {
        return $this->belongsToMany('App\User');
    }

    public function bonus() {
        return $this->hasOne('App\Bonus', 'id', 'bonus_id');
    }

    public static function findUserBonus($user_id, $bonus_id, $promo_code='') {
        return UserBonus::where('user_id', $user_id)
            ->where('bonus_id', $bonus_id)
            ->where('promo_code', $promo_code)
            ->first();
    }

    public static function availableBonuses($user) {
        $bonuses = Bonus::where('current','1')
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
        ->whereNull('user_bonus.bonus_id') // check if is not in not used or consumed
        ->get();

        return $bonuses;
    }

    /**
     * Get the user active bonuses
     *
     * @param $user
     * @return UserBonus
     */
    public static function activeBonuses($user) {
        return UserBonus::where('user_id', $user->id)
            ->where('active','1')
            ->get();
    }

    /**
     * Get the user consumed bonuses
     *
     * @param $user
     * @return mixed
     */
    public static function consumedBonuses($user) {
        return UserBonus::where('user_id', $user->id)
            ->where('active','0')
            ->get();
    }

    /**
     * Cancel a user specific bonus
     *
     * @param $user
     * @param $id
     * @return UserBonus
     */
    public static function cancelBonus($user, $id) {
        $bonus = UserBonus::find($id);
        if ($bonus) {
            DB::transaction(function() use ($user, $bonus) {
                $bonus->active = 0;
                $bonus->save();
                $bonusAmount = $user->balance->getBonus();
                $user->balance->subtractBonus($bonusAmount);
            });
        }
        return $bonus;
    }

    /**
     * Redeems a bonus available to the user
     *
     * @param $user
     * @param $bonus_id
     * @param string $bonus_origin
     * @return UserBonus
     */
    public static function redeemBonus($user, $bonus_id, $bonus_origin='sport') {
        DB::beginTransaction();
        try {
            if (UserBonus::findActiveBonusByOrigin($user, $bonus_origin))
                throw new Exception();
            $bonus = Bonus::find($bonus_id);
            $userBonus = UserBonus::create([
                'user_id' => $user->id,
                'bonus_id' => $bonus_id,
                'bonus_head_id' => $bonus->head_id,
                'deadline_date' => Carbon::now()->addDay($bonus->deadline),
                'active' => 1,
            ]);
            if (($userBonus->bonus->bonus_type_id !== 'first_deposit') && ($userBonus->bonus->bonus_type_id !== 'deposits'))
                $user->balance->addBonus($userBonus->bonus->amount);
        } catch (Exception $e) {
            DB::rollBack();
            return null;
        }
        DB::commit();
        return $userBonus;
    }

    /**
     * Find user active bonus by origin
     * @param $user
     * @param $bonus_origin
     * @return mixed
     */
    public static function findActiveBonusByOrigin($user, $bonus_origin) {
        return UserBonus::select ('user_bonus.*')
            ->where('user_id', $user->id)
            ->where('active', '1')
            ->leftJoin('bonus', 'user_bonus.bonus_id', '=', 'bonus.id')
            ->where('bonus_origin_id', $bonus_origin)
            ->first();
    }


    protected function isBonusAbleToDeposit($trans) {
        return ($trans->debit >= $this->bonus->min_deposit
            && $trans->debit <= $this->bonus->max_deposit
            && ($this->bonus->apply_deposit_methods === 'all'
                || $this->bonus->apply_deposit_methods === $trans->origin));
        }

    public function isFirstDepositBonusAllowed($user, $trans) {
        $depositCount = $user->transactions->where('status_id', 'processed')->count();
        return $this->isBonusAbleToDeposit($trans) && $depositCount === 1 && $this->bonus->bonus_type_id === 'first_deposit';
    }

    public function isDepositsBonusAllowed($trans) {
        return $this->isBonusAbleToDeposit($trans) && $this->bonus->bonus_type_id === 'deposits' && $this->deposited === 0;
    }

    public function applyFirstDepositBonus($user, $trans) {
        $user->balance->addBonus($trans->debit * ($this->bonus->value / 100));
        $this->rollover_coefficient = $this->bonus->rollover_coefficient * ($trans->debit + $user->balance->getBonus());
        $this->deposited = 1;
        $this->save();
    }

    public function applyDepositsBonus($user, $trans) {
        $user->balance->addBonus($this->bonus->value_type === 'percentage' ? $trans->debit * ($this->bonus->value / 100) : $this->bonus->value);
        $this->rollover_coefficient = $this->bonus->rollover_coefficient * ($trans->debit + $user->balance->getBonus());
        $this->deposited = 1;
        $this->save();
    }
}