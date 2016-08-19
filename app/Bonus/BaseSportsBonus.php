<?php

namespace App\Bonus;


use App\Bets\Bets\Bet;
use App\Bonus;
use App\User;
use App\UserBet;
use App\UserBonus;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Lang;

class BaseSportsBonus
{
    protected $_user;

    protected $_userBonus;

    public function __construct(User $user=null, UserBonus $userBonus=null)
    {
        $this->_user = $user ? $user : Auth::user();

        $this->_userBonus = $userBonus ? $userBonus
            : $this->_user ? $this->getActive()->first() : null;
    }

    public static function make(User $user=null)
    {
        $user = $user ? $user : Auth::user();

        if (!$user)
            return new static;

        $activeBonus = UserBonus::fromUser($user->id)
            ->active()
            ->with('bonus')
            ->first();

        if (!$activeBonus)
            return new static($user);

        if ($activeBonus->bonus->bonus_type_id === 'first_deposit')
            return new FirstDeposit($user, $activeBonus);

        return new static($user);
    }

    public function getAvailable($columns = ['*'])
    {
        return Bonus::availableBonuses($this->_user)
            ->with('bonusType')
            ->get($columns);
    }

    public function getActive($columns = ['*'])
    {
        return UserBonus::fromUser($this->_user->id)
            ->active()
            ->get($columns);
    }

    public function getConsumed($columns = ['*'])
    {
        return UserBonus::fromUser($this->_user->id)
            ->consumed()
            ->get($columns);
    }

    public function hasActive()
    {
        return UserBonus::fromUser($this->_user->id)
            ->active()
            ->count() > 0;
    }

    public function isAvailable($bonusId)
    {
        return Bonus::availableBonuses($this->_user)
                ->hasBonus($bonusId)
                ->count() > 0;
    }

    public function redeem($bonusId)
    {
        $this->__selfExcludedCheck();

        if (!$this->isAvailable($bonusId))
            throw new SportsBonusException(Lang::get('bonus.redeem.error'));

        DB::transaction(function() use ($bonusId) {
            $bonus = Bonus::findOrFail($bonusId);

            $userBonus = [
                'user_id' => $this->_user->id,
                'bonus_id' => $bonusId,
                'bonus_head_id' => $bonus->head_id,
                'deadline_date' => Carbon::now()->addDay($bonus->deadline),
                'active' => 1,
            ];

            UserBonus::create($userBonus);
        });
    }

    public function cancel()
    {
        throw new SportsBonusException(Lang::get('bonus.cancel.error'));
    }

    public function isCancellable()
    {
        return false;
    }

    public function isAutoCancellable()
    {
        return false;
    }

    public function isPayable()
    {
        return false;
    }

    public function addWagered($amount) {}

    public function subtractWagered($amount) {}

    public function applicableTo(Bet $bet)
    {
        return false;
    }

    public function depositNotify($trans) {}

    public function pay() {}

    public function hasId($bonusId)
    {
        return $this->_userBonus->id == $bonusId;
    }

    protected function __selfExcludedCheck()
    {
        if ($this->_user->isSelfExcluded())
            throw new SportsBonusException(Lang::get('bonus.self_excluded.error'));
    }

    protected function __deactivate()
    {
        DB::transaction(function() {
            $this->_userBonus->active = 0;
            $this->_userBonus->save();

            $balance = $this->_user->balance->fresh();

            $bonusAmount = $balance->balance_bonus;
            if ($bonusAmount)
                $balance->subtractBonus($bonusAmount);
        });
    }

    protected function __hasUnresolvedBets()
    {
        return UserBet::fromUser($this->_user->id)
            ->waitingResult()
            ->fromBonus($this->_userBonus->id)
            ->count() > 0;
    }

    public function foo()
    {
        return 'base';
    }

    public function userBonus()
    {
        return $this->_userBonus;
    }
}