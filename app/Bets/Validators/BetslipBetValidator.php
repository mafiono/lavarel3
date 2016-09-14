<?php

namespace App\Bets\Validators;

use App\Bets\Bets\Bet;
use App\Bets\Bets\BetException;
use App\Bets\Cashier\ChargeCalculator;
use App\GlobalSettings;
use App\UserBonus;
use App\UserLimit;
use App\UserBet;
use Exception;
use SportsBonus;

class BetslipBetValidator extends BetValidator
{
    private $user;

    public function __construct(Bet $bet)
    {
        $this->user = $bet->user;

        parent::__construct($bet);
    }

    private function checkSelfExclusion()
    {
        if (!$this->user->status->isApproved())
            throw new BetException("Utilizador está impedido de apostar.");
    }

    private function checkPlayerDailyLimit()
    {
        $dailyLimit = (float) UserLimit::GetCurrLimitValue('limit_betting_daily');
        if ($dailyLimit) {
            $dailyAmount = UserBet::dailyAmount($this->user->id);
            if (($dailyAmount + $this->bet->amount) > $dailyLimit)
                throw new BetException('Limite diário ultrapassado');
        }
    }
    private function checkPlayerWeeklyLimit()
    {
        $weeklyLimit = (float) UserLimit::GetCurrLimitValue('limit_betting_weekly');
        if ($weeklyLimit) {
            $weeklyAmount = UserBet::weeklyAmount($this->user->id);
            if (($weeklyAmount + $this->bet->amount) > $weeklyLimit)
                throw new BetException('Limite semanal ultrapassado');
        }
    }
    private function checkPlayerMonthlyLimit()
    {
        $monthlyLimit = (float) UserLimit::GetCurrLimitValue('limit_betting_monthly');
        if ($monthlyLimit) {
            $monthlyAmount = UserBet::monthlyAmount($this->user->id);
            if (($monthlyAmount + $this->bet->amount) > $monthlyLimit)
                throw new BetException('Limite mensal ultrapassado');
        }
    }

    private function checkAvailableBalance()
    {
        if (!(new ChargeCalculator($this->bet, SportsBonus::applicableTo($this->bet)))->chargeable())
            throw new BetException('Saldo insuficiente');
    }

    private function checkLowerBetLimit()
    {
        if ($this->bet->amount < GlobalSettings::getBetLowerLimit())
            throw new BetException('O limite inferior é de 2 euros');
    }

    private function checkUpperBetLimit()
    {
        if ($this->bet->amount > GlobalSettings::getBetUpperLimit())
            throw new BetException('O limite superior é de 100 euros');
    }

    private function checkApproved()
    {
        if (!$this->user->status->isApproved())
            throw new BetException('Utilizador não está aprovado');
    }

    protected function checkConstrains()
    {
        $this->checkApproved();
        $this->checkSelfExclusion();
        $this->checkSelfExclusion();
        $this->checkLowerBetLimit();
        $this->checkUpperBetLimit();
        $this->checkPlayerDailyLimit();
        $this->checkPlayerWeeklyLimit();
        $this->checkPlayerMonthlyLimit();
        $this->checkAvailableBalance();
    }


}