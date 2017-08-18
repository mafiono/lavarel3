<?php

namespace App\Bonus\Sports;

use App\Bets\Bets\Bet;
use App\Bets\Cashier\ChargeCalculator;
use App\Bonus;
use App\Bonus\BaseBonus;
use App\Events\SportsBonusWasCancelled;
use App\Events\SportsBonusWasRedeemed;
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

abstract class BaseSportsBonus extends BaseBonus
{
    protected static function bonus(User $user, UserBonus $userBonus)
    {
        switch (($userBonus->bonus->bonus_type_id)) {
            case 'first_deposit':
                return new FirstDeposit($user, $userBonus);
            case 'first_bet':
                return new FirstBet($user, $userBonus);
        }

        return static::noBonus($user);
    }

    protected static function noBonus(User $user = null)
    {
        return new NoBonus($user);
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
            $this->throwException(Lang::get('bonus.redeem.error'));
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

            event(new SportsBonusWasRedeemed($userBonus));

            $mail = new SendMail(SendMail::$TYPE_8_BONUS_ACTIVATED);
            $mail->prepareMail($this->user, [
                'title' => 'Bónus',
                'url' => '/promocoes',
            ], $userSession->id);
            $mail->Send(false);
        });
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

        event(new SportsBonusWasCancelled($this->userBonus));
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

    public function applicableTo(Bet $bet, $throwReason = false)
    {
        try {
            if ($bet->type !== 'multi') {
                $this->throwException("Aposta tem de ser múltipla");
            }

            if ($bet->user->balance->balance_bonus <= 0 || !(new ChargeCalculator($bet))->chargeable) {
                $this->throwException("Montante tem de ser igual a {$this->user->balance->balance_bonus} €");
            }

            if ((Carbon::now() > $this->userBonus->deadline_date)) {
                $this->throwException("Bonus expirado");
            }

            if (($bet->odd < $this->userBonus->bonus->min_odd)) {
                $this->throwException("Não cumpre odd mínima de {$this->userBonus->bonus->min_odd}");
            }

            if ($bet->lastEvent()->game_date > $this->userBonus->deadline_date) {
                $this->throwException("Início do evento ultrapassa expiração do bónus");
            }

            if ($bet->events->count() < 3) {
                $this->throwException("Não cumpre mínimo de 3 apostas");
            }

            if (!$this->hasAllEventsAboveMinOdds($bet)) {
                $this->throwException("As apostas não podem ser de odd inferior a 1.3");
            }
        } catch (SportsBonusException $e) {
            if ($throwReason) {
                throw $e;
            }

            return false;
        }

        return true;
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

    public function bonusAmount(Bonus $bonus = null)
    {
        if (!is_null($bonus)) {
            switch (($bonus->bonus_type_id)) {
                case 'first_deposit':
                    return (new FirstDeposit(Auth::user(), null))->bonusAmount($bonus);
                case 'first_bet':
                    return (new FirstBet(Auth::user(), null))->bonusAmount($bonus);
            }
        }

        return 0;
    }

    protected function hasAllEventsAboveMinOdds($bet)
    {
        return $bet->events->filter(function ($event) {
            return $event->odd < GlobalSettings::getFirstDepositEventMinOdds();
        })->isEmpty();
    }

    protected function throwException($message = null)
    {
        throw new SportsBonusException($message);
    }
}
