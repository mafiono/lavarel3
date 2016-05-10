<?php


namespace App\Bets\Bets;

use App\UserBet;
use Faker;

class BetResultFaker extends UserBet
{

    public function __construct(array $attributes)
    {
        $faker = Faker\Factory::create();
        if ($this->status !== 'waiting_result')
            throw new \Exception('User already has a result');
        $this->status = $this->rand()<1/$this->odd?'won':'lost';

        parent::__construct($attributes);
    }

    private function rand()
    {
        return rand()/getrandmax();
    }


}