<?php

namespace App\Bets;

use Exception;
use Validator;


abstract class BetValidator
{
    protected $user;
    protected $bet;

    protected function __construct(Bet $bet)
    {
        $this->bet = $bet;
        $this->user = $bet->getUser();
    }

    public function validate()
    {
        $this->checkBetData();

        $this->statusValidation();

        return true;
    }

    protected function checkBetData() {
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

    abstract protected function statusValidation();

}