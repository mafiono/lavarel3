<?php

namespace app\Bets\Bets;


use App\GlobalSettings;
use App\UserBet;
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

        $this->save();

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
        $this->save();

        return $this;
    }

    public function setLostResult()
    {
        $this->result = 'lost';
        $this->status = 'lost';
        $this->save();

        return $this;
    }

    public function cancelBet()
    {
        $this->status = 'cancelled';
        $this->save();

        return $this;
    }

    public function save(array $options = [])
    {
        $result = parent::save($options);

        UserBetStatus::createFromBet($this);

        return $result;
    }

}