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
        'username' => $faker->userName,
        'password' => bcrypt('123456'),
        'security_pin' => '1234',
        'identity_checked' => '1',
        'identity_method' => null,
        'identity_date' => $faker->dateTimeBetween('-1 year'),
        'user_code' => strtoupper(str_random(5)),
        'promo_code' => strtoupper(str_random(5)),
        'currency' => 'euro',
        'user_role_id' => 'player',
        'api_token' => str_random(20),
        'api_password' => str_random(20),
        'remember_token' => str_random(40),
        'rating_risk' => 'Risk0',
        'rating_group' => 'Walk',
        'rating_type' => 'Mouse',
        'rating_category' => 'Sportbooks',
        'rating_class' => 'Test',
        'rating_status' => 'active',
        'ggr_sb' => 0,
        'ggr_casino' => 0,
        'margin_sb' => 0,
        'margin_casino' => 0,
    ];
});

$factory->define(App\UserProfile::class, function (Faker\Generator $faker) {
    return [
        'gender' => $faker->randomElement(['m', 'f']),
        'name' => $faker->name,
        'email' => $faker->email,
        'email_checked' => 1,
        'email_token' => str_random(10),
        'birth_date' => $faker->dateTimeBetween('-90 year', '-18 year'),
        'nationality' => 'PT',
        'professional_situation' => $faker->randomElement([11,22,33,44,55,66,77,88,99]),
        'profession' => $faker->word,
        'address' => $faker->address,
        'zip_code' => $faker->postcode,
        'city' => $faker->city,
        'phone' => $faker->phoneNumber,
        'country' => 'PT',
        'document_number' => $faker->numberBetween(5000),
        'tax_number' => $faker->numerify('##############'),
    ];
});

$factory->define(App\UserBalance::class, function (Faker\Generator $faker) {
    return [
        'balance_available' => $faker->numberBetween(1000, 2000),
        'b_av_check' => 0,
        'balance_captive' => 0,
        'b_ca_check' => 0,
        'balance_bonus' => 0,
        'b_bo_check' => 0,
        'balance_accounting' => 0,
        'b_ac_check' => 0,
        'balance_total' => 0,
        'b_to_check' => 0,
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
        'available_until' => $faker->dateTimeBetween('+ 1 day','+ 20 day')
    ];
});
