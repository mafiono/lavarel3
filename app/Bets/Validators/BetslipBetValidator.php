<?php

namespace App\Bets\Validators;

use App\UserBonus;
use App\UserLimit;
use App\UserBet;
use Exception;


class BetslipBetValidator extends BetValidator
{
    private $user;

    public function __construct(UserBet $bet)
    {
        $this->user = $bet->user;

        parent::__construct($bet);
    }

    private function checkSelfExclusion()
    {
        if ($this->user->isSelfExcluded())
            throw new Exception("Utilizador está auto-excluído.");
    }

    private function checkPlayerDailyLimit() {
        $dailyLimit = (float) UserLimit::GetCurrLimitValue('limit_betting_daily');
        if ($dailyLimit) {
            $dailyAmount = UserBet::dailyAmount($this->user->id);
            if (($dailyAmount + $this->bet->amount) > $dailyLimit)
                throw new Exception('Limite diário ultrapassado');
        }
    }
    private function checkPlayerWeeklyLimit() {
        $weeklyLimit = (float) UserLimit::GetCurrLimitValue('limit_betting_weekly');
        if ($weeklyLimit) {
            $weeklyAmount = UserBet::weeklyAmount($this->user->id);
            if (($weeklyAmount + $this->bet->amount) > $weeklyLimit)
                throw new Exception('Limite semanal ultrapassado');
        }
    }
    private function checkPlayerMonthlyLimit() {
        $monthlyLimit = (float) UserLimit::GetCurrLimitValue('limit_betting_monthly');
        if ($monthlyLimit) {
            $monthlyAmount = UserBet::monthlyAmount($this->user->id);
            if (($monthlyAmount + $this->bet->amount) > $monthlyLimit)
                throw new Exception('Limite mensal ultrapassado');
        }
    }

    private function checkAvailableBalance() {
        $balance = $this->user->balance->balance_available + (UserBonus::canUseBonus($this->bet)?$this->user->balance->balance_bonus:0);
        if ($balance < $this->bet->amount)
            throw new Exception('Saldo insuficiente'.$this->user->balance->balance_available);
    }

    private function checkLowerBetLimit() {
        if ($this->bet->amount < 2)
            throw new Exception('O limite inferior é de 2 euros');
    }

    private function checkUpperBetLimit() {
        if ($this->bet->amount > 100)
            throw new Exception('O limite superior é de 100 euros');
    }

    protected function statusValidation()
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