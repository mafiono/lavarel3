<?php


namespace App\Bets\Bets;

use App\UserBet;

class BetResultFaker
{

    public static function setResult(UserBet $bet)
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