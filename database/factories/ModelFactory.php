<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'username' => $faker->name,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'rating_risk' => 'Risk0',
        'user_role_id' => 'player'
    ];
});


$factory->define(App\Bonus::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence(),
        'description' => $faker->paragraph(),
        'value' => $faker->numberBetween(50,100),
        'target' => 'all',
        'bonus_origin_id' => 'sport',
        'available_from' => $faker->dateTimeBetween('- 20 day','- 1 day'),
        'available_until' => $faker->dateTimeBetween('+ 1 day','+ 20 day'),
    ];
});
