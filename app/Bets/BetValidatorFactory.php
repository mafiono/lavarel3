<?php


namespace App\Bets;


class BetValidatorFactory
{

    public static function make(Bet $bet)
    {
        if ($bet->getStatus() === 'won')
            return new BetResultBetValidator($bet);

        return new BetslipBetValidator($bet);
    }
}