<?php

namespace App\Bets;

use App\UserBets;

abstract class Bet
{
    protected $user;
    protected $bet;
    protected $apiType;
    protected $apiId;
    protected $apiTransId;
    protected $rid;
    protected $amount;
    protected $odd;
    protected $type;
    protected $status;
    protected $gameDate;


    public function getUser()
    {
        return $this->user;
    }

    public function getApiType()
    {
        return $this->apiType;
    }

    public function getApiId()
    {
        return $this->apiId;
    }

    public function getApiTransactionId()
    {
        return $this->apiTransId;
    }

    public function getRid()
    {
        return $this->rid;
    }

    public function getAmount()
    {
        return (float) $this->amount;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getOdd()
    {
        return (float) $this->odd;
    }

    public function getGameDate()
    {
        return $this->gameDate;
    }

    public function getStatus() {
        return $this->status;
    }

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