<?php

namespace App\Betslip;

use App\UserSession;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class Bet
{
    private $user;
    private $bet;

    public function __construct($bet)
    {
        $this->user = Auth::user();
        $this->bet = $bet;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getRid()
    {

        return $this->bet['rid'];
    }

    public function getAmount()
    {
        return (float)$this->bet['amount'];
    }

    public function getType()
    {
        return $this->bet['type'];
    }

    public function getOdd()
    {
        if ($this->bet['type'] === 'simple')
            return (float)$this->bet['events'][0]['price'];

        return array_reduce($this->bet['events'], function($carry, $event) {
            return is_null($carry)?$event['odd']:$carry*$event['odd'];
        });
    }

    public function bet() {
        return $this->bet;
    }

    private function placeBet() {
        DB::beginTransaction();
        $newbet = [
            'amount' => $this->getAmount(),
            'currency' => 'eur',
            'initial_balance' => $this->user->balance->balance_available,
            'initial_bonus' => $this->user->balance->balance_bonus,
            'status' => 'waiting_result',
            'user_session_id' => UserSession::getSessionId()
        ];

        $this->user->balance->subtractAvailableBalance($this->getAmount());
        $newbet['final_balance'] = $this->user->balance->balance_available;
        $newbet['final_bonus'] = $this->user->balance->balance_bonus;

        DB::rollBack();
    }

    private function makeBetArray() {

    }


}