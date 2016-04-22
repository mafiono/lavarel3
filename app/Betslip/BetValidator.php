<?php

namespace App\Betslip;

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
            $dailyAmount = (float) UserBets::dailyAmount($this->user);
            if (($dailyAmount + $this->bet->getAmount()) > $dailyLimit)
                throw new Exception('Limite diário ultrapassado');
        }
    }
    private function checkPlayerWeeklyLimit() {
        $weeklyLimit = (float) UserLimit::GetCurrLimitValue('limit_betting_weekly');
        if ($weeklyLimit) {
            $weeklyAmount = (float) UserBets::weeklyAmount($this->user);
            if (($weeklyAmount + $this->bet->getAmount()) > $weeklyLimit)
                throw new Exception('Limite semanal ultrapassado');
        }
    }
    private function checkPlayerMonthlyLimit() {
        $monthlyLimit = (float) UserLimit::GetCurrLimitValue('limit_betting_monthly');
        if ($monthlyLimit) {
            $monthlyAmount = (float) UserBets::monthlyAmount($this->user);
            if (($monthlyAmount + $this->bet->getAmount()) > $monthlyLimit)
                throw new Exception('Limite mensal ultrapassado');
        }
    }

    private function checkAvailableBonus() {
        if ($this->user->balance->getBonus() < $this->bet->getAmount())
            throw new Exception('Bónus insuficiente');
    }

    private function checkBonusMinOdd() {
        if ($this->bet->getOdd() < $this->activeBonus->bonus->min_odd)
            throw new Exception('Cota mínima do bónus ultrapassada');
    }

    private function checkBonusDeadlineDate() {
        if ($this->bet->getGameDate() > $this->activeBonus->deadline_date)
            throw new Exception('Data limite do bónus ultrapassada');
    }

    private function checkAvailableBalance() {
        if ($this->user->balance->getAvailableBalance() < $this->bet->getAmount())
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

        if (UserBonus::hasActiveBonus($this->user->id)) {
            $this->checkAvailableBonus();
            $this->checkBonusMinOdd();
            $this->checkBonusDeadlineDate();
        } else
            $this->checkAvailableBalance();
    }

}