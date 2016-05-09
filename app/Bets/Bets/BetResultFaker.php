<?php


namespace App\Bets\Bets;

use App\UserBet;
use Faker;

class BetResultFaker extends UserBet
{
    public function __construct()
    {
        $faker = Faker\Factory::create();
        if ($this->status !== 'waiting_result')
            throw new \Exception('User already has a result');
        $this->status = $faker->randomElement(['won' , 'lost']);
        parent::__construct();
    }
}