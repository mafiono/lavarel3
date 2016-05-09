<?php

namespace App\Bets\Validators;

use App\UserBetEvent;
use Exception;
use Validator;
use App\UserBet;


abstract class BetValidator
{
    protected $bet;


    protected $betRules =[
        'user_id' => 'required|exists:users,id',
        'amount' => 'required|numeric|min:0',
        'odd' => 'required|numeric|min:1',
        'status' => 'required|string',
        'type' => 'required|string',
    ];

    protected $eventRules = [
//        'user_bet_id' => 'required|exists:user_bets,id',
        'odd' => 'required|numeric|min:1',
        'status' => 'required|string',
        'event_name' => 'required|string',
        'market_name' => 'required|string',
        'game_name' => 'required|string',
        'game_date' => 'required|date',
        'api_event_id' => 'required|numeric:1',
        'api_market_id' => 'required|numeric:1',
        'api_game_id' => 'required|numeric:1',
    ];

    protected function __construct(UserBet $bet)
    {
        $this->bet = $bet;
    }

    public function validate()
    {
        $this->validateBet();

        $this->statusValidation();

        return true;
    }

    protected function validateBet()
    {
        $validator = Validator::make($this->bet->toArray(), $this->betRules);

        if ($validator->fails())
            throw new Exception('Dados da aposta incorrectos.'.$validator->errors());

        foreach ($this->bet->events as $event)
            $this->validateEvent($event);
    }

    protected function validateEvent(UserBetEvent $event)
    {
        $validator = Validator::make($event->toArray(), $this->eventRules);
        if ($validator->fails())
            throw new Exception('Dados do event incorrectos.'.$validator->errors());
    }

    abstract protected function statusValidation();

}