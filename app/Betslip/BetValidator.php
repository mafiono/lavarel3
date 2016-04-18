<?php

namespace App\Betslip;

use App\UserBonus;
use App\UserLimit;
use Exception;

class BetValidator
{
    protected $error;
    protected $user;
    protected $bet;
    protected $userBonus;

    public function __construct(Bet $bet)
    {
        $this->bet = $bet;
        $this->user = $bet->getUser();
        $this->userBonus = UserBonus::findActiveBonusByOrigin($this->user, 'sport');
    }

    protected function checkSelfExclusion()
    {
        if ($this->user->isSelfExcluded())
            throw new Exception("Utilizador está auto-excluído.");
    }

    protected function checkPlayerDailyLimit() {}
    protected function checkPlayerWeeklyLimit() {}
    protected function checkPlayerMonthlyLimit() {}

    protected function checkAvailableBonus() {
        if ($this->user->balance->getBonus()<$this->bet->getAmount())
            throw new Exception('Bónus insuficiente');
    }

    protected function checkBonusMinOdd() {
        if ($this->bet->getOdd()<$this->userBonus->bonus->min_odd)
            throw new Exception('Cota mínima do bónus ultrapassada');
    }

    protected function checkBonusDeadlineDate() {}

    protected function checkAvailableBalance() {
        if ($this->user->balance->getAvailableBalance()<$this->bet->getAmount())
            throw new Exception('Saldo insuficiente');
    }

    protected function checkLowerBetLimit() {
        if ($this->bet->getAmount()<2)
           throw new Exception('O limite inferior é de 2 euros');
    }
    protected function checkUpperBetLimit() {
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