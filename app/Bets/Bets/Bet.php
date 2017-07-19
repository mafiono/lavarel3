<?php

namespace App\Bets\Bets;


use App\UserBet;
use App\UserBetEvent;
use App\UserBetStatus;
use App\UserSession;

class Bet extends UserBet
{
    public function placeBet($sessionId = null)
    {
        $this->currency = 'eur';
        $this->status = 'waiting_result';
        $this->user_session_id = $sessionId ?: UserSession::getSessionId();

        $this->store();

        foreach ($this->events as $event) {
            $event->user_bet_id = $this->id;
            $event->save();
        }

        return $this;
    }

    public function calcPartialOdd()
    {
        $odd = 1;
        foreach ($this->events as $event) {
            if ($event->status === 'won') {
                $odd *= $event->odd;
            }
        }
        $this->odd = $odd;
        $this->save();

        return $this;
    }

    public function setWonResult()
    {
        $this->result = 'won';
        $this->result_amount = $this->amount*$this->odd;
        $this->status = 'won';


        $this->store();

        return $this;
    }

    public function setLostResult()
    {
        $this->result = 'lost';
        $this->status = 'lost';
        $this->store();

        return $this;
    }

    public function cancelBet()
    {
        $this->status = 'cancelled';
        $this->store();

        return $this;
    }

    public function returnBet()
    {
        $this->status = 'returned';
        $this->store();

        return $this;
    }

    public function hasUnresolvedEvents()
    {
        $count = UserBetEvent::unresolved()
            ->where('user_bet_id', $this->id)
            ->count();

        return $count > 0;
    }

    public function hasReturnedEvents() {
        return UserBetEvent::where('user_bet_id', $this->id)
            ->where('status', 'returned')
            ->exists();
    }

    public function hasWonEvents() {
        return UserBetEvent::where('user_bet_id', $this->id)
            ->where('status', 'won')
            ->exists();
    }

    public function store()
    {
        $this->save();

        UserBetStatus::createFromBet($this);

        $this->api_bet_id = $this->id;

        $this->save();
    }

}