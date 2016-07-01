<?php


namespace App\Bets\Bets;


class BetResultFaker
{

    public static function setResult(Bet $bet)
    {
        if ($bet->status !== 'waiting_result')
            throw new \Exception('User already has a result');

        $bet->status = static::rand()<1/($bet->odd*1.1)?'won':'lost';

        return $bet;
    }

    private static function rand()
    {
        return rand()/getrandmax();
    }

}