<?php

namespace App\Bonus;

use App\Bets\Bets\Bet;
use App\Bets\Cashier\ChargeCalculator;
use App\Bonus;
use App\User;
use App\UserBet;
use App\UserBonus;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Lang;
use SportsBonus;

abstract class BaseSportsBonus
{
    protected $user;

    protected $userBonus;

    public function __construct(User $user = null, UserBonus $userBonus = null)
    {
        $this->user = $user ? $user : Auth::user();

        $this->userBonus = $userBonus ? $userBonus
            : $this->user ? $this->getActive() : null;
    }

    public static function make(User $user = null, UserBonus $bonus = null)
    {
        $user = $user ? $user : Auth::user();

        if (!$user) {
            return new EmptyBonus();
        }

        $activeBonus = $bonus ?: UserBonus::activeFromUser($user->id, ['bonus'])->first();

        if (is_null($activeBonus)) {
            return new EmptyBonus($user);
        }

        switch (($activeBonus->bonus->bonus_type_id)) {
            case 'first_deposit':
                return new FirstDeposit($user, $activeBonus);
            case 'friend_invite':
                return new FriendInvite($user, $activeBonus);
        }

        return new EmptyBonus($user);
    }

    public function getAvailable($columns = ['*'])
    {
        return Bonus::availableBonuses($this->user)
            ->with('bonusType')
            ->get($columns);
    }

    public function getActive($columns = ['*'])
    {
        return UserBonus::fromUser($this->user->id)
            ->active()
            ->first($columns);
    }

    public function getConsumed($columns = ['*'])
    {
        return UserBonus::fromUser($this->user->id)
            ->consumed()
            ->get($columns);
    }

    public function hasActive()
    {
        return UserBonus::fromUser($this->user->id)
            ->active()
            ->count() > 0;
    }

    public function isAvailable($bonusId)
    {
        return Bonus::availableBonuses($this->user)
                ->hasBonus($bonusId)
                ->exists();
    }

    public function redeem($bonusId)
    {
        $this->selfExcludedCheck();

        if (!$this->isAvailable($bonusId) || $this->hasActive()) {
            throw new SportsBonusException(Lang::get('bonus.redeem.error'));
        }

        DB::transaction(function () use ($bonusId) {
            $bonus = Bonus::findOrFail($bonusId);

            $userBonus = UserBonus::create([
                'user_id' => $this->user->id,
                'bonus_id' => $bonusId,
                'bonus_head_id' => $bonus->head_id,
                'deadline_date' => Carbon::now()->addDay($bonus->deadline),
                'active' => 1,
            ]);

            SportsBonus::swapBonus($userBonus);

            SportsBonus::deposit();
        });
    }

    public function swapBonus(UserBonus $bonus = null)
    {
        app()->instance('sports.bonus', BaseSportsBonus::make($this->user, $bonus));

        SportsBonus::swap(app()->make('sports.bonus'));
    }

    public function swapUser(User $user, UserBonus $bonus = null)
    {
        app()->instance('sports.bonus', BaseSportsBonus::make($user, $bonus));

        SportsBonus::swap(app()->make('sports.bonus'));
    }

    public function cancel()
    {
        $this->selfExcludedCheck();

        if (!$this->isCancellable()) {
            throw new SportsBonusException(Lang::get('bonus.cancel.error'));
        }

        $this->deactivate();
    }

    public function forceCancel()
    {
        $this->deactivate();
    }

    public function isCancellable()
    {
        return !$this->hasUnresolvedBets();
    }

    public function isAutoCancellable()
    {
        return $this->userBonus->deposited == 1
            && $this->user->balance->fresh()->balance_bonus == 0
            && $this->isCancellable();
    }

    public function addWagered($amount)
    {
        $this->userBonus = UserBonus::lockForUpdate()->find($this->userBonus->id);
        $this->userBonus->bonus_wagered += $amount;
        $this->userBonus->save();
    }

    public function subtractWagered($amount)
    {
        $this->userBonus = UserBonus::lockForUpdate()->find($this->userBonus->id);
        $this->userBonus->bonus_wagered -= $amount;
        $this->userBonus->save();
    }

    public function hasId($bonusId)
    {
        return $this->userBonus->id == $bonusId;
    }

    public function userBonus()
    {
        return $this->userBonus;
    }

    public function getBonusType()
    {
        return $this->userBonus && $this->userBonus->bonus ? $this->userBonus->bonus->bonus_type_id : '';
    }

    protected function selfExcludedCheck()
    {
        if ($this->user->isSelfExcluded()) {
            throw new SportsBonusException(Lang::get('bonus.self_excluded.error'));
        }
    }

    protected function deactivate()
    {
        DB::transaction(function () {
            $this->userBonus->active = 0;
            $this->userBonus->save();

            $balance = $this->user->balance->fresh();
            $bonusAmount = $balance->balance_bonus*1;
            if ($bonusAmount) {
                $balance->subtractBonus($bonusAmount);
            }
        });
    }

    protected function hasUnresolvedBets()
    {
        return UserBet::fromUser($this->user->id)
            ->waitingResult()
            ->fromBonus($this->userBonus->id)
            ->count() > 0;
    }

    public function applicableTo(Bet $bet)
    {
        return ($bet->user->balance->balance_bonus > 0)
        && (new ChargeCalculator($bet))->chargeable()
        && (Carbon::now() <= $this->userBonus->deadline_date)
        && ($bet->odd >= $this->userBonus->bonus->min_odd)
        && ($bet->lastEvent()->game_date <= $this->userBonus->deadline_date);
    }

    public function isPayable()
    {
        return false;
    }
}
