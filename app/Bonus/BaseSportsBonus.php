<?php

namespace App\Bonus;

use App\Bets\Bets\Bet;
use App\Bets\Cashier\ChargeCalculator;
use App\Bonus;
use App\GlobalSettings;
use App\Lib\Mail\SendMail;
use App\User;
use App\UserBet;
use App\UserBonus;
use App\UserTransaction;
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

        $this->userBonus = !is_null($userBonus)
            ? $userBonus
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
            case 'first_bet':
                return new FirstBet($user, $activeBonus);
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

    public function hasAvailable()
    {
        return Bonus::availableBonuses($this->user)
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

            $userSession = $this->user->logUserSession('bonus.redeem', 'Redeem Bonus: '. $bonus->title);

            $userBonus = UserBonus::create([
                'user_id' => $this->user->id,
                'user_session_id' => $userSession->id,
                'bonus_id' => $bonusId,
                'bonus_head_id' => $bonus->head_id,
                'deadline_date' => Carbon::now()->addDay($bonus->deadline),
                'active' => 1,
            ]);

            SportsBonus::swapBonus($userBonus);

            SportsBonus::deposit();

            $mail = new SendMail(SendMail::$TYPE_8_BONUS_ACTIVATED);
            $mail->prepareMail($this->user, [
                'title' => 'Bónus',
                'url' => '/promocoes',
            ], $userSession->id);
            $mail->Send(false);
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
        return !$this->hasUnresolvedBetsFromBonus();
    }

    public function isAutoCancellable()
    {
        return $this->isCancellable();
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

    public function refreshUser()
    {
        $this->user = $this->user->fresh();
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

            UserTransaction::forceCreate([
                'user_id' => $this->user->id,
                'origin' => 'sport_bonus',
                'transaction_id' => UserTransaction::getHash($this->user->id, Carbon::now()),
                'credit_bonus' => $bonusAmount,
                'initial_balance' => $balance->balance_available,
                'initial_bonus' => $bonusAmount,
                'final_balance' => $balance->balance_available,
                'final_bonus' => $balance->balance_bonus,
                'date' => Carbon::now(),
                'description' => 'Término de bónus ' . $this->userBonus->bonus->title,
                'status_id' => 'processed',
            ]);
        });

        SportsBonus::swapBonus();
    }

    protected function hasUnresolvedBetsFromBonus()
    {
        return $this->hasBetsWithStatus('waiting_result');
    }


    protected function hasUnresolvedBets($excludes = [])
    {
        return $this->hasBetsWithStatus('waiting_result', false, $excludes);
    }

    protected function hasWonBets()
    {
        return $this->hasBetsWithStatus('won');
    }

    protected function hasBetsWithStatus($status, $fromBonus=true, $excludes=[])
    {
        $excludes = array_filter($excludes, function($exclude) {
            return !is_null($exclude);
        });

        $query = UserBet::fromUser($this->user->id)
            ->whereStatus($status)
            ->whereNotIn('id', $excludes);


        if ($fromBonus) {
            $query->fromBonus($this->userBonus->id);
        }

        return $query->exists();
    }

    public function applicableTo(Bet $bet)
    {
        return ($bet->type === 'multi')
            && ($bet->user->balance->balance_bonus > 0)
            && (new ChargeCalculator($bet))->chargeable
            && (Carbon::now() <= $this->userBonus->deadline_date)
            && ($bet->odd >= $this->userBonus->bonus->min_odd)
            && ($bet->lastEvent()->game_date <= $this->userBonus->deadline_date)
            && ($bet->events->count() > 2)
            && $this->hasAllEventsAboveMinOdds($bet);

    }

    public function isPayable()
    {
        return false;
    }

    public function deposit()
    {
    }

    public function isAppliedToBet(Bet $bet)
    {
        return $this->userBonus->id === $bet->user_bonus_id;
    }

    protected function hasAllEventsAboveMinOdds($bet)
    {
        return $bet->events->filter(function ($event) {
            return $event->odd < GlobalSettings::getFirstDepositEventMinOdds();
        })->isEmpty();
    }
}
