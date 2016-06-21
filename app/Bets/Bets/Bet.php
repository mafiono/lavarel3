<?php

namespace App\Bets\Bets;


use App\GlobalSettings;
use App\UserBet;
use App\UserBetEvent;
use App\UserBetStatus;
use App\UserSession;

class Bet extends UserBet
{

    public function placeBet()
    {
        $this->amount_taxed = $this->amount * GlobalSettings::getTax();
        $this->currency = 'eur';
        $this->status = 'waiting_result';
        $this->user_session_id = UserSession::getSessionId();

        $this->store();

        foreach ($this->events as $event) {
            $event->user_bet_id = $this->id;
            $event->save();
        }

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

    public function hasUnresolvedEvents()
    {
        $count = UserBetEvent::unresolved()
            ->where('user_bet_id', $this->id)
            ->count();

        return $count > 0;
    }

    public function store()
    {
        $this->save();

        UserBetStatus::createFromBet($this);

        $this->api_bet_id = $this->id;

        $this->save();
    }

}