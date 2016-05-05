<?php

namespace App\Bets;

use App\UserBonus;
use App\UserLimit;
use App\UserBet;
use Exception;
use Validator;


class BetslipBetValidator extends BetValidator
{
    public function __construct(Bet $bet)
    {
        parent::__construct($bet);
    }

    public function checkBetData() {
        $rules = [
            'apiType' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'odd' => 'required|numeric|min:1',
            'status' => 'required|string',
            'gameDate' => 'required|date',
            'userId' => 'required|exists:users,id',
        ];
        if ($this->bet->getApiType() !== 'betportugal')
            $rules['apiId'] = 'required|numeric';

        $validator = Validator::make($this->bet->toArray(), $rules);

        if ($validator->fails()) {
            logger($validator->errors());
            throw new Exception('Dados da aposta incorrectos.');
        }
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
            if (($dailyAmount + $this->bet->getAmount()) > $dailyLimit)
                throw new Exception('Limite diário ultrapassado');
        }
    }
    private function checkPlayerWeeklyLimit() {
        $weeklyLimit = (float) UserLimit::GetCurrLimitValue('limit_betting_weekly');
        if ($weeklyLimit) {
            $weeklyAmount = UserBet::weeklyAmount($this->user->id);
            if (($weeklyAmount + $this->bet->getAmount()) > $weeklyLimit)
                throw new Exception('Limite semanal ultrapassado');
        }
    }
    private function checkPlayerMonthlyLimit() {
        $monthlyLimit = (float) UserLimit::GetCurrLimitValue('limit_betting_monthly');
        if ($monthlyLimit) {
            $monthlyAmount = UserBet::monthlyAmount($this->user->id);
            if (($monthlyAmount + $this->bet->getAmount()) > $monthlyLimit)
                throw new Exception('Limite mensal ultrapassado');
        }
    }

    private function checkAvailableBalance() {
        $balance = $this->user->balance->balance_available + (UserBonus::canUseBonus($this->bet)?$this->user->balance->balance_bonus:0);
        if ($balance < $this->bet->getAmount())
            throw new Exception('Saldo insuficiente'.$this->user->balance->balance_available);
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
        $this->checkBetData();
        $this->statusValidation();

        return true;
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