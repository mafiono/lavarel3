<?php

namespace App\Bets\Validators;

use App\Bets\Bets\Bet;
use App\Bets\Bets\BetException;
use App\Bets\Cashier\ChargeCalculator;
use App\GlobalSettings;
use App\Models\CasinoTransaction;
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
        if ($this->user->status->isSelfExcluded())
            throw new BetException("Utilizador está auto-excluído.");
    }

    private function checkPlayerDailyLimit()
    {
        $dailyLimit = (float) UserLimit::GetCurrLimitValue('limit_betting_daily');

        if (!$dailyLimit) {
            return;
        }

        $betAmount = $this->bet->amount;

        $dailySportsAmount = UserBet::dailyAmount($this->user->id);

        $dailyCasinoAmount = CasinoTransaction::dailyAmount($this->user->id);

        if ($dailyLimit < ($dailySportsAmount + $dailyCasinoAmount + $betAmount)) {
                throw new BetException('Limite diário ultrapassado');
        }
    }
    private function checkPlayerWeeklyLimit()
    {
        $weeklyLimit = (float) UserLimit::GetCurrLimitValue('limit_betting_weekly');

        if (!$weeklyLimit) {
            return;
        }

        $betAmount = $this->bet->amount;

        $weeklySportsAmount = UserBet::weeklyAmount($this->user->id);

        $weeklyCasinoAmount = CasinoTransaction::weeklyAmount($this->user->id);

        if ($weeklyLimit < ($weeklySportsAmount + $weeklyCasinoAmount + $betAmount)) {
                throw new BetException('Limite semanal ultrapassado');
        }
    }

    private function checkPlayerMonthlyLimit()
    {
        $monthlyLimit = (float) UserLimit::GetCurrLimitValue('limit_betting_monthly');

        if (!$monthlyLimit) {
            return;
        }

        $betAmount = $this->bet->amount;

        $monthlySportsAmount = UserBet::monthlyAmount($this->user->id);

        $monthlyCasinoAmount = CasinoTransaction::monthlyAmount($this->user->id);

        if ($monthlyLimit < ($monthlySportsAmount + $monthlyCasinoAmount + $betAmount)) {
            throw new BetException('Limite mensal ultrapassado');
        }
    }

    private function checkAvailableBalance()
    {
        if (!(new ChargeCalculator($this->bet, SportsBonus::applicableTo($this->bet)))->chargeable)
            throw new BetException('Saldo insuficiente');
    }

    private function checkLowerBetLimit()
    {
        $minBetAmount = GlobalSettings::getBetLowerLimit();

        if ($this->bet->amount < $minBetAmount)
            throw new BetException('O limite inferior é de '. $minBetAmount . ' euro');
    }

    private function checkMinOdds()
    {
        if ($this->bet->odd < 1.08) {
            throw new BetException('A cota mínima é 1.08');
        }
    }

    private function checkPrizeUpperLimit()
    {
        $maxPrize = GlobalSettings::getPrizeUpperLimit();

        if (($this->bet->amount * $this->bet->odd) > $maxPrize)
            throw new BetException('O prémio limite é de ' . $maxPrize . ' euros');
    }

    private function checkApproved()
    {
        if (!$this->user->status->isApproved())
            throw new BetException('Utilizador não está aprovado');
    }

    protected function checkConstrains()
    {
        $this->checkSelfExclusion();
        $this->checkApproved();
        $this->checkLowerBetLimit();
        $this->checkMinOdds();
        $this->checkPrizeUpperLimit();
        $this->checkPlayerDailyLimit();
        $this->checkPlayerWeeklyLimit();
        $this->checkPlayerMonthlyLimit();
        $this->checkAvailableBalance();
    }


}