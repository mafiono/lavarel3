<?php

namespace App\Bets;

use App\UserBonus;
use App\UserLimit;
use App\UserBet;
use Exception;
use Validator;


class BetResultBetValidator
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

    public function validate()
    {
        $this->checkBetData();
        $this->statusValidation();

        return true;
    }

    protected function statusValidation()
    {
        $userBet = UserBet::findByApi($this->bet->getApiType(), $this->bet->getApiId());
        if (is_null($userBet))
            throw new Exception('Bet does not exists.');
        if ($userBet->status_id !== 'waiting_result')
            throw new Exception('Bet is closed.');
    }

}