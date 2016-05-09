<?php

namespace App\Bets\Bets;

use App\User;
use App\UserBet;
use Auth;
use Faker;

class FakePlaceBet extends UserBet
{
    private $rid;
    public function __construct(User $user, array $bet=[])
    {
        $user = $user ?: Auth::user();
        $this->user_id = $user->id;

        $faker = Faker\Factory::create();
        $this->api_bet_type = isset($bet['apiType']) ? $bet['apiType'] : $faker->randomElement(['betportugal', 'everymatrix']);
        $this->api_bet_id =  isset($bet['apiId']) ? $bet['apiId'] : $faker->unique()->numberBetween();
        $this->api_transaction_id = isset($bet['apiTransId']) ? $bet['apiTransId'] : $faker->unique()->numberBetween();
        $this->rid = isset($bet['rid']) ? $bet['rid'] : $faker->numberBetween(0, 99);
        $this->amount = isset($bet['amount']) ? $bet['amount'] : $faker->numberBetween(2, 50);
        $this->odd = isset($bet['odd']) ? $bet['odd'] : $faker->numberBetween(100,1000)/100;
        $this->type = isset($bet['type']) ? $bet['type'] : $faker->randomElement(['simple', 'multi']);
//        $this->game_date = isset($bet['gameDate']) ? $bet['gameDate'] : $faker->dateTimeBetween('-20 day');


    }

    public function getRidAttribute()
    {
        return $this->rid;
    }

}