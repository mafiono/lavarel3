<?php

namespace App\Bets\Validators;

use App\Bets\Bets\Bet;
use App\Bets\Bets\BetException;
use App\Bets\Cashier\ChargeCalculator;
use App\Bonus\Sports\SportsBonusException;
use App\GlobalSettings;
use App\Models\CasinoTransaction;
use App\UserBonus;
use App\UserLimit;
use App\UserBet;
use Carbon\Carbon;
use Exception;
use SportsBonus;

class BetslipBetValidator extends BetValidator
{
    protected $user;

    public function __construct(Bet $bet)
    {
        $this->user = $bet->user;

        parent::__construct($bet);
    }

    protected function checkSelfExclusion()
    {
        if ($this->user->status->isSelfExcluded())
            throw new BetException("Utilizador está auto-excluído.");
    }

    protected function checkPlayerDailyLimit()
    {
        $dailyLimit = (float) UserLimit::GetCurrLimitValue($this->user->id, 'limit_betting_daily');

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
    protected function checkPlayerWeeklyLimit()
    {
        $weeklyLimit = (float) UserLimit::GetCurrLimitValue($this->user->id, 'limit_betting_weekly');

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

    protected function checkPlayerMonthlyLimit()
    {
        $monthlyLimit = (float) UserLimit::GetCurrLimitValue($this->user->id, 'limit_betting_monthly');

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

    protected function checkAvailableBalance()
    {
        if (!(new ChargeCalculator($this->bet, SportsBonus::applicableTo($this->bet)))->chargeable)
            throw new BetException('Saldo insuficiente');
    }

    protected function checkLowerBetLimit()
    {
        $minBetAmount = GlobalSettings::getBetLowerLimit();

        if ($this->bet->amount < $minBetAmount)
            throw new BetException('O limite inferior é de '. $minBetAmount . ' euro');
    }

    protected function checkMinOdds()
    {
        if ($this->bet->odd < 1.08) {
            throw new BetException('A cota mínima é 1.08');
        }
    }

    protected function checkPrizeUpperLimit()
    {
        $maxPrize = GlobalSettings::getPrizeUpperLimit();

        if (($this->bet->amount * $this->bet->odd) > $maxPrize)
            throw new BetException('O prémio limite é de ' . $maxPrize . ' euros');
    }

    protected function checkApproved()
    {
        if (!$this->user->status->isApproved())
            throw new BetException('Utilizador não está aprovado');
    }

    protected function checkWeeklyPrizeUpperLimit()
    {
        $maxPrize = GlobalSettings::getWeeklyPrizeUpperLimit();

        $totalPrize = UserBet::whereUserId($this->user->id)
                ->where('created_at', '>', Carbon::now()->subWeek())
                ->whereIn('status', ['waiting_result', 'won'])
                ->get(['amount', 'odd'])
                ->reduce(function ($carry, $bet) {
                    return $carry + ($bet->amount * $bet->odd);
                })
            + $this->bet->amount * $this->bet->odd;

        if ($totalPrize > $maxPrize) {
            throw new BetException('O prémio semanal limite é de ' . $maxPrize . ' euros');
        }
    }

    protected function checkAllIn()
    {
        if (SportsBonus::hasActive()
            && SportsBonus::getBonusType() === 'first_bet'
            && $this->user->balance->balance_bonus > 0
        ) {
            try {
                SportsBonus::applicableTo($this->bet, true);
            } catch (SportsBonusException $e) {
                throw new BetException($e->getMessage());
            }
        }
    }

    protected function checkConstrains()
    {
        $this->checkSelfExclusion();
        $this->checkApproved();
        $this->checkLowerBetLimit();
        $this->checkMinOdds();
        $this->checkPrizeUpperLimit();
        $this->checkWeeklyPrizeUpperLimit();
        $this->checkPlayerDailyLimit();
        $this->checkPlayerWeeklyLimit();
        $this->checkPlayerMonthlyLimit();
        $this->checkAllIn();
        $this->checkAvailableBalance();
    }
}