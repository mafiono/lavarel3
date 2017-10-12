<?php

namespace App\Bonus\Sports;

use App\Bets\Bets\Bet;
use App\Bets\Cashier\ChargeCalculator;
use App\Bonus;
use App\Bonus\BaseBonus;
use App\Events\SportsBonusWasCancelled;
use App\Events\SportsBonusWasRedeemed;
use App\GlobalSettings;
use App\User;
use App\UserBet;
use App\UserBonus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

abstract class BaseSportsBonus extends BaseBonus
{
    protected $origin = 'sport';

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

    protected static function activeUserBonus($userId, $origin = null)
    {
        return parent::activeUserBonus($userId, 'sport');
    }

    public function getAvailable($columns = ['*'])
    {
        return Bonus::origin($this->origin)
            ->availableBonuses($this->user)
            ->with('bonusType')
            ->get($columns);
    }

    public function hasActive()
    {
        return UserBonus::fromUser($this->user->id)
            ->active()
            ->origin($this->origin)
            ->count() > 0;
    }

    public function isAvailable($bonusId)
    {
        return Bonus::origin($this->origin)
                ->availableBonuses($this->user)
                ->hasBonus($bonusId)
                ->exists();
    }

    public function hasAvailable()
    {
        return Bonus::origin($this->origin)
            ->availableBonuses($this->user)
            ->exists();
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

    public function getBonusType()
    {
        return $this->userBonus && $this->userBonus->bonus ? $this->userBonus->bonus->bonus_type_id : '';
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

    public function isAppliedToBet(Bet $bet)
    {
        return $this->userBonus->id === $bet->user_bonus_id;
    }

    public function previewRedeemAmount(Bonus $bonus = null)
    {
        if (!is_null($bonus)) {
            switch (($bonus->bonus_type_id)) {
                case 'first_deposit':
                    return (new FirstDeposit(Auth::user(), null))->redeemAmount($bonus);
                case 'first_bet':
                    return (new FirstBet(Auth::user(), null))->redeemAmount($bonus);
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

    protected function canceledEvent(UserBonus $userBonus)
    {
        event(new SportsBonusWasCancelled($userBonus));
    }

    protected function redeemedEvent(UserBonus $userBonus)
    {
        event(new SportsBonusWasRedeemed($userBonus));
    }

    protected function throwException($message = null)
    {
        throw new SportsBonusException($message);
    }
}
