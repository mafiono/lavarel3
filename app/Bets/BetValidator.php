<?php

namespace App\Bets;

use App\UserBets;
use App\UserBonus;
use App\UserLimit;
use Exception;

class BetValidator
{
    private $user;
    private $bet;
    private $activeBonus;

    public function __construct(Bet $bet)
    {
        $this->bet = $bet;
        $this->user = $bet->getUser();
        $this->activeBonus = UserBonus::getActiveBonus($this->user->id);
    }

    private function checkSelfExclusion()
    {
        if ($this->user->isSelfExcluded())
            throw new Exception("Utilizador está auto-excluído.");
    }

    private function checkPlayerDailyLimit() {
        $dailyLimit = (float) UserLimit::GetCurrLimitValue('limit_betting_daily');
        if ($dailyLimit) {
            $dailyAmount = UserBets::dailyAmount($this->user->id);
            if (($dailyAmount + $this->bet->getAmount()) > $dailyLimit)
                throw new Exception('Limite diário ultrapassado');
        }
    }
    private function checkPlayerWeeklyLimit() {
        $weeklyLimit = (float) UserLimit::GetCurrLimitValue('limit_betting_weekly');
        if ($weeklyLimit) {
            $weeklyAmount = UserBets::weeklyAmount($this->user->id);
            if (($weeklyAmount + $this->bet->getAmount()) > $weeklyLimit)
                throw new Exception('Limite semanal ultrapassado');
        }
    }
    private function checkPlayerMonthlyLimit() {
        $monthlyLimit = (float) UserLimit::GetCurrLimitValue('limit_betting_monthly');
        if ($monthlyLimit) {
            $monthlyAmount = UserBets::monthlyAmount($this->user->id);
            if (($monthlyAmount + $this->bet->getAmount()) > $monthlyLimit)
                throw new Exception('Limite mensal ultrapassado');
        }
    }

    private function checkAvailableBonus() {
        if ($this->user->balance->getBonus() < $this->bet->getAmount())
            throw new Exception('Bónus insuficiente');
    }

    private function canUseBonus() {
        return ($this->bet->getOdd() >= $this->activeBonus->bonus->min_odd) && ($this->bet->getGameDate() <= $this->activeBonus->deadline_date);
    }

    private function checkAvailableBalance() {
        $balance = $this->user->balance->balance_available + $this->canUseBonus()?$this->user->balance->balance_bonus:0;

        if ($balance < $this->bet->getAmount())
            throw new Exception('Saldo insuficiente');
    }

    private function checkLowerBetLimit() {
        if ($this->bet->getAmount() < 2)
           throw new Exception('O limite inferior é de 2 euros');
    }

    private function checkUpperBetLimit() {
        if ($this->bet->getAmount() > 100)
            throw new Exception('O limite superior é de 100 euros');
    }

    public function validate()
    {
        $this->checkSelfExclusion();
        $this->checkLowerBetLimit();
        $this->checkUpperBetLimit();
        $this->checkPlayerDailyLimit();
        $this->checkPlayerWeeklyLimit();
        $this->checkPlayerMonthlyLimit();
        $this->checkAvailableBalance();
    }

}