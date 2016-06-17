<?php

namespace App\Bets\Validators;

use App\Bets\Bets\Bet;
use App\Bets\Verifier\BetVerifier;
use App\UserBetEvent;
use Exception;
use Validator;


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

    protected function __construct(Bet $bet)
    {
        $this->bet = $bet;
    }

    public static function make(Bet $bet)
    {
        return new static($bet);
    }

    public function validate()
    {
        $this->validateBet();

        $this->checkConstrains();

        $this->verifyBet();

        return true;
    }

    protected function validateBet()
    {
        $validator = Validator::make($this->bet->toArray(), $this->betRules);

        if ($validator->fails())
            throw new Exception('Dados da aposta incorrectos.');

        foreach ($this->bet->events as $event)
            $this->validateEvent($event);
    }

    protected function validateEvent(UserBetEvent $event)
    {
        $validator = Validator::make($event->toArray(), $this->eventRules);

        if ($validator->fails())
            throw new Exception('Dados do event incorrectos.');

    }

    protected function verifyBet()
    {
        BetVerifier::make($this->bet)->verify();
    }

    abstract protected function checkConstrains();

}