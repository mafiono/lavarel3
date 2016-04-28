<?php

namespace App\Bets;

use App\UserBets;

abstract class Bet
{
    protected $user;
    protected $bet;

    public function getUser() {return $this->user;}

    abstract public function getApiType();

    abstract public function getApiId();

    public abstract function getApiTransactionId();

    public abstract function getRid();

    public abstract function getAmount();

    public abstract function getType();

    public abstract function getOdd();

    public abstract function getStatus();

    public abstract function getGameDate();

    public function placeBet()
    {
        return BetBookie::placeBet($this);
    }

    public function setWonResult()
    {
        return BetBookie::setWonResult($this);
    }

    public function toArray() {
        return [
            'apiType' => $this->getApiType(),
            'apiId' => $this->getApiId(),
            'amount' => $this->getAmount(),
            'type' => $this->getType(),
            'odd' => $this->getOdd(),
            'status' => $this->getStatus(),
            'gameDate' => $this->getGameDate(),
            'userId' => $this->getUser()->id,
        ];
    }

}