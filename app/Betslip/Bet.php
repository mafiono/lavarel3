<?php

namespace App\Betslip;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Bet
{
    private $user;
    protected $bet;

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
            return (float)$this->bet['odds'][0]['price'];

        return array_reduce($this->bet['odds'], function($carry, $odd) {
            return is_null($carry)?$odd['price']:$carry*$odd['price'];
        });
    }

    public function bet() {
        return $this->bet;
    }
}