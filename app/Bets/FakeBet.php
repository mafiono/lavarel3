<?php

namespace App\Bets;

use App\User;
use App\UserBets;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Faker;

class FakeBet extends Bet
{
    private $apiType;
    private $apiId;
    private $apiTransId;
    private $rid;
    private $amount;
    private $odd;
    private $type;
    private $status;
    private $gameDate;

    public function __construct(User $user, array $attributes=[])
    {
        $this->user = $user ?: Auth::user();
        $faker = Faker\Factory::create();

        $this->apiType = isset($attributes['apiType']) ? $attributes['apiType'] : $faker->randomElement(['betportugal2', 'everymatrix']);
        $this->apiId =  isset($attributes['apiId']) ? $attributes['apiId'] : $faker->unique()->numberBetween();
        $this->apiTransId = isset($attributes['apiTransId']) ? $attributes['apiTransId'] : $faker->unique()->numberBetween();
        $this->rid = isset($attributes['rid']) ? $attributes['rid'] : $faker->numberBetween(0, 99);
        $this->amount = isset($attributes['amount']) ? $attributes['amount'] : $faker->numberBetween(2, 50);
        $this->odd = isset($attributes['odd']) ? $attributes['odd'] : $faker->numberBetween(100,1000)/100;
        $this->type = isset($attributes['type']) ? $attributes['type'] : $faker->randomElement(['simple', 'multi']);
        $this->status = isset($attributes['status']) ? $attributes['status'] : $faker->randomElement(['waiting_result',
            'won',
            'lost',
            'cancelled'
        ]);
        $this->gameDate = isset($attributes['gameDate']) ? $attributes['gameDate'] : $faker->dateTimeBetween('-20 day');


    }

    public function getApiType()
    {
        return $this->apiType;
    }

    public function getApiId()
    {
        return $this->apiId;
    }

    public function getApiTransactionId() {
        return $this->apiTransId;
    }

    public function getRid()
    {
        return $this->rid;
    }

    public function getAmount()
    {
        return (float) $this->amount;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getOdd()
    {
        return $this->odd;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getGameDate() {
        return $this->gameDate;
    }

}