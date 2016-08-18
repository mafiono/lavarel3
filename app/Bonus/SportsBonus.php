<?php

namespace App\Bonus;


use App\Bets\Bets\Bet;
use App\Bonus;
use App\User;
use App\UserBalance;
use App\UserBet;
use App\UserBonus;
use App\UserTransaction;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Lang;

class SportsBonus
{
    protected $user;

    public function __construct(User $user=null)
    {
        $this->setUser($user);
    }

    public static function make(User $user=null)
    {
        $sportsBonus = new static;

        $activeBonus = $sportsBonus->getActive()->first();

        if ($activeBonus
            && $activeBonus->bonus
            && $activeBonus->bonus->bonus_type_id === 'first_deposit')
            return new FirstDeposit($user);

        return $sportsBonus;
    }

    public function setUser(User $user=null)
    {
        return $this->user = $user ? $user : Auth::user();
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getAvailable($columns = ['*'])
    {
        return Bonus::availableBonuses($this->user)
            ->with('bonusType')
            ->get($columns);
    }

    public function getActive($columns = ['*'])
    {
        return UserBonus::belongsToUser($this->user->id)
            ->active()
            ->get($columns);
    }

    public function getConsumed($columns = ['*'])
    {
        return UserBonus::belongsToUser($this->user->id)
            ->consumed()
            ->get($columns);
    }

    public function hasActive()
    {
        return UserBonus::belongsToUser($this->user->id)
            ->active()
            ->count() > 0;
    }

    public function isAvailable($bonusId)
    {
        return Bonus::availableBonuses($this->user)
                ->hasBonus($bonusId)
                ->count() > 0;
    }

    public function redeem($bonusId)
    {
        $this->selfExcludedCheck();

        if (!$this->isAvailable($bonusId))
            throw new SportsBonusException(Lang::get('bonus.redeem.error'));

        DB::transaction(function() use ($bonusId) {
            $bonus = Bonus::findOrFail($bonusId);

            $userBonus = [
                'user_id' => $this->user->id,
                'bonus_id' => $bonusId,
                'bonus_head_id' => $bonus->head_id,
                'deadline_date' => Carbon::now()->addDay($bonus->deadline),
                'active' => 1,
            ];

            UserBonus::create($userBonus);
        });
    }

    public function cancel($bonusId)
    {
        $this->selfExcludedCheck();

        if (!$this->isCancellable($bonusId))
            throw new SportsBonusException(Lang::get('bonus.cancel.error'));

        $this->deactivate($bonusId);
    }

    private function deactivate($bonusId)
    {
        DB::transaction(function() use ($bonusId) {
            $userBonus = UserBonus::findOrFail($bonusId);
            $userBonus->active = 0;
            $userBonus->save();

            $balance = $this->user->balance->fresh();
            $bonusAmount = $balance->balance_bonus;
            if ($bonusAmount)
                $balance->subtractBonus($bonusAmount);
        });
    }
    private function noUnresolvedBets($bonusId)
    {
        return UserBet::fromUser($this->user->id)
            ->waitingResult()
            ->fromBonus($bonusId)
            ->count() === 0;
    }

    public function isCancellable($bonusId)
    {
        return $this->noUnresolvedBets($bonusId);
    }

    public function isAutoCancellable($bonusId)
    {
        $userBonus = UserBonus::find($bonusId);

        $balance = $this->user->balance->fresh();

        return $userBonus->deposited === 1
            && $balance->balance_bonus === 0
            && $this->isCancellable($bonusId);
    }

    public function isPayable($bonusId)
    {
        $userBonus = UserBonus::find($bonusId);

        return $userBonus->deposited === 1
            && $userBonus->bonus_wagered >= $userBonus->rollover_amount
            && $this->noUnresolvedBets($bonusId)
            && (Carbon::now() <= $userBonus->deadline_date);
    }


    private function selfExcludedCheck()
    {
        if ($this->user->isSelfExcluded())
            throw new SportsBonusException(Lang::get('bonus.self_excluded.error'));
    }

    public function addWagered($amount)
    {
        $userBonus = $this->getActive();

        $userBonus->bonus_wagered += $amount;

        $userBonus->save();
    }

    public function subtractWagered($amount)
    {
        $userBonus = $this->getActive();

        $userBonus->bonus_wagered -= $amount;

        $userBonus->save();
    }

    public function applicableTo(Bet $bet)
    {
        $userBonus = $this->getActive()->first();

        return !is_null($userBonus)
        && ($bet->user->balance->balance_bonus > 0)
        && (new ChargeCalculator($bet))->chargeable()
        && (Carbon::now() <= $userBonus->deadline_date)
        && ($bet->odd >= $userBonus->bonus->min_odd)
        && ($bet->lastEvent()->game_date <= $userBonus->deadline_date)
        && ($userBonus->bonus_wagered < $userBonus->rollover_amount);
    }

    public function depositNotify($trans)
    {
        $depositsCount = UserTransaction::deposistsFromUser($this->user->id)->count();

        if ($depositsCount === 1)
        {
            $userBonus = $this->getActive();

            $balance = $this->user->balance->fresh();
            $bonusAmount = min($trans->debit * $userBonus->bonus->value * 0.01, GlobalSettings::maxFirstDepositBonus());

            $balance->addBonus($bonusAmount);

            $rolloverAmount = $userBonus->rollover_coefficient * min($bonusAmount + $trans->debit, 2 * GlobalSettings::maxFirstDepositBonus());

            $userBonus->bonus_value = $bonusAmount;
            $userBonus->rollover_amount = $rolloverAmount;
            $userBonus->deposited = 1;

            $this->save();
        }
    }

    public function pay($bonusId)
    {
        $balance = $this->user->balance->fresh();

        $balance->addAvailableBalance($balance->balance_bonus);

        $this->deactivate($bonusId);

        UserTransaction::createTransaction(
            $balance->balance_bonus,
            $this->user->id,
            'BONUS'.$bonusId,
            'deposit',
            null,
            null
        );
    }

    function foo()
    {
        return 'base bonus';
    }
}