<?php

use App\Bets\Validators\BetslipBetValidator;

class BetValidator extends BetslipBetValidator
{
    public function __construct($bet)
    {
        $this->bet = $bet;

        $this->user = $bet->user;
    }

    public function checkAllIn()
    {
        parent::checkAllIn();
    }
}