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
    protected $dates = ['deadline_date'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function bonus()
    {
        return $this->belongsTo(Bonus::class);
    }

    /**
     * @param $user_id
     * @param $bonus_id
     * @param string $promo_code
     * @return mixed
     */
    public static function findUserBonus($user_id, $bonus_id, $promo_code='') {
        return UserBonus::where('user_id', $user_id)
            ->where('bonus_id', $bonus_id)
            ->where('promo_code', $promo_code)
            ->first();
    }

    public function scopeBelongsToUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeCountActive($query) {
        return $query->count();
    }

    public function scopeActive($query)
    {
        return $query->where('active', '1');
    }

    public static function scopeConsumed($query)
    {
        return $query->where('active', '0');
    }

    /**
     * Get the user consumed bonuses
     *
     * @param $user
     * @return mixed
     */
    public static function getConsumedBonuses($user_id) {
        return self::consumedBonuses($user_id)
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
        if ($bonus && $bonus->active) {
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
            if (UserBonus::findActiveBonusByOrigin($user, $bonus_origin) && Bonus::isBonusAvailable($user, $bonus_id))
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

    /**
     * @param $trans
     * @return bool
     */
    protected function isDepositValidForBonus($trans) {
        return ($trans->debit >= $this->bonus->min_deposit
            && $trans->debit <= $this->bonus->max_deposit
            && ($this->bonus->apply_deposit_methods === 'all'
                || $this->bonus->apply_deposit_methods === $trans->origin));
    }

    /**
     * @param $user
     * @param $trans
     * @return bool
     */
    public function canApplyFirstDepositBonus($user, $trans) {
        $depositCount = $user->transactions->where('status_id', 'processed')->count();
        return $this->isDepositValidForBonus($trans) && $depositCount === 1 && $this->bonus->bonus_type_id === 'first_deposit';
    }

    /**
     * @param $trans
     * @return bool
     */
    public function canApplyDepositsBonus($trans) {
        return $this->isDepositValidForBonus($trans) && $this->bonus->bonus_type_id === 'deposits' && $this->deposited === 0;
    }

    /**
     * @param $user
     * @param $trans
     */
    public function applyFirstDepositBonus($user, $trans) {
        $user->balance->addBonus($trans->debit * ($this->bonus->value / 100));
        $this->bonus_value = $user->balance->balance_bonus;
        $this->rollover_amount = $this->bonus->rollover_coefficient * ($trans->debit + $user->balance->getBonus());
        $this->deposited = 1;
        $this->save();
    }

    /**
     * @param $user
     * @param $trans
     */
    public function applyDepositsBonus($user, $trans) {
        $user->balance->addBonus($this->bonus->value_type === 'percentage' ? $trans->debit * ($this->bonus->value / 100) : $this->bonus->value);
        $this->bonus_value = $user->balance->balance_bonus;
        $this->rollover_amount = $this->bonus->rollover_coefficient * ($trans->debit + $user->balance->getBonus());
        $this->deposited = 1;
        $this->save();
    }

    /**
     * @param Bet $bet
     * @return bool
     */
    public static function canUseBonus(UserBet $bet)
    {
        $activeBonus = UserBonus::getActiveBonus($bet->user_id);

        return !is_null($activeBonus) &&
            ($bet->user->balance->balance_bonus>0) &&
            (Carbon::now() <= $activeBonus->deadline_date) &&
            ($bet->odd >= $activeBonus->bonus->min_odd) &&
            ($bet->lastEventDate() <= $activeBonus->deadline_date);
    }

    /**
     * @param $amount
     */
    public function addWageredBonus($amount)
    {
        $this->freshLockForUpdate();

        $this->bonus_wagered += $amount;

        $this->save();
    }

    /**
     * @return bool
     */
    public function rolloverCriteriaReached()
    {
        return $this->bonus_value>=$this->bonus_wagered && $this->user->balance->balance_bonus>=$this->rollover_amount;
    }

    //TODO: make trait
    private function freshLockForUpdate()
    {
        $this->forceFill((static::where('id', $this->id)->lockForUpdate()->first()->attributesToArray()));
    }
}