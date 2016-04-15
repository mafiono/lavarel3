<?php

namespace App\Betslip;

use Exception;

class BetValidator
{
    protected $error;
    protected $user;
    protected $bet;

    public function __construct(Bet $bet)
    {
        $this->bet = $bet;
        $this->user = $bet->getUser();
    }

    protected function checkSelfExclusion()
    {
        if ($this->user->isSelfExcluded())
            throw new Exception("Utilizador está auto-excluído.");
    }
    protected function checkPlayerDailyLimit() {}
    protected function checkPlayerWeeklyLimit() {}
    protected function checkPlayerMonthlyLimit() {}
    protected function checkAvailableBonus() {}
    protected function checkAvailableBalance() {}

    public function validate()
    {
        $this->checkSelfExclusion();
    }
}