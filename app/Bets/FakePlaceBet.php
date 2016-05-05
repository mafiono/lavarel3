<?php

namespace App\Bets;

use App\User;
use App\UserBets;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Faker;

class FakePlaceBet extends Bet
{

    public function __construct(User $user, array $bet=[])
    {
        $this->user = $user ?: Auth::user();
        $faker = Faker\Factory::create();

        $this->apiType = isset($bet['apiType']) ? $bet['apiType'] : $faker->randomElement(['betportugal2', 'everymatrix']);
        $this->apiId =  isset($bet['apiId']) ? $bet['apiId'] : $faker->unique()->numberBetween();
        $this->apiTransId = isset($bet['apiTransId']) ? $bet['apiTransId'] : $faker->unique()->numberBetween();
        $this->rid = isset($bet['rid']) ? $bet['rid'] : $faker->numberBetween(0, 99);
        $this->amount = isset($bet['amount']) ? $bet['amount'] : $faker->numberBetween(2, 50);
        $this->odd = isset($bet['odd']) ? $bet['odd'] : $faker->numberBetween(100,1000)/100;
        $this->type = isset($bet['type']) ? $bet['type'] : $faker->randomElement(['simple', 'multi']);
        $this->status = 'waiting_result';
        $this->gameDate = isset($bet['gameDate']) ? $bet['gameDate'] : $faker->dateTimeBetween('-20 day');
    }

}