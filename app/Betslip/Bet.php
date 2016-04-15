<?php

namespace App\Betslip;

use Illuminate\Database\Eloquent\Model;
use App\Betslip\BetValidator;
use Auth;

class Bet
{
    protected $user;
    protected $id;
    protected $type;
    protected $amount;
    protected $odds;

    public function __construct($bet)
    {
        $this->user = Auth::user();
        $this->type = $bet['type'];
        $this->amount = $bet['amount'];
        $this->odds = $bet['odds'];
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getAmount()
    {
        return $this->amount;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getOdd()
    {
        if ($this->type === 'single')
            return (int)$this->odds[0];
        return 0;
    }

}