<?php

namespace App\Betslip;

use App\UserBonus;
use App\UserLimit;
use Exception;

class BetValidator
{
    private $user;
    private $bet;
    private $userBonus;

    public function __construct(Bet $bet)
    {
        $this->bet = $bet;
        $this->user = $bet->getUser();
        $this->userBonus = UserBonus::findActiveBonusByOrigin($this->user, 'sport');
    }

    private function checkSelfExclusion()
    {
        if ($this->user->isSelfExcluded())
            throw new Exception("Utilizador está auto-excluído.");
    }

    private function checkPlayerDailyLimit() {}
    private function checkPlayerWeeklyLimit() {}
    private function checkPlayerMonthlyLimit() {}

    private function checkAvailableBonus() {
        if ($this->user->balance->getBonus()<$this->bet->getAmount())
            throw new Exception('Bónus insuficiente');
    }

    private function checkBonusMinOdd() {
        if ($this->bet->getOdd()<$this->userBonus->bonus->min_odd)
            throw new Exception('Cota mínima do bónus ultrapassada');
    }

    private function checkBonusDeadlineDate() {}

    private function checkAvailableBalance() {
        if ($this->user->balance->getAvailableBalance()<$this->bet->getAmount())
            throw new Exception('Saldo insuficiente');
    }

    private function checkLowerBetLimit() {
        if ($this->bet->getAmount()<2)
           throw new Exception('O limite inferior é de 2 euros');
    }

    private function checkUpperBetLimit() {
        if ($this->bet->getAmount()>100)
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
        if ($this->userBonus) {
            $this->checkAvailableBonus();
            $this->checkBonusMinOdd();
            $this->checkBonusDeadlineDate();
        } else
            $this->checkAvailableBalance();
    }
}