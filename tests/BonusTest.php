<?php

use App\User;
use App\Bonus;
use App\UserBonus;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class BonusTest extends TestCase {
    use DatabaseTransactions;

    public function setUp() {
        parent::setUp();
        $user = User::findByUsername("miguel");
        $this->be($user);
    }


    public function testUserManyToManyRelationToBonus() {
        UserBonus::create([
            'user_id' => Auth::user()->id,
            'bonus_id' => 53
        ]);

        $this->assertTrue(Auth::user()->bonuses()->count()>=1);
    }

    public function testAvailableBonuses() {
        $faker = Faker\Factory::create();
    }
}