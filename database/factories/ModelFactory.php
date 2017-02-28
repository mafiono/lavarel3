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
        'identity_date' => $faker->dateTimeBetween('-1 year'),
        'user_code' => strtoupper(str_random(5)),
        'user_role_id' => 'player',
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

$factory->define(App\UserSession::class, function (Faker\Generator $faker) {
    return [
        'session_number' => 1,
        'session_id' => str_random(),
    ];
});

$factory->define(App\UserBalance::class, function (Faker\Generator $faker) {
    return [];
});

$factory->define(App\Bonus::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence(),
        'description' => $faker->paragraph(),
        'value' => $faker->numberBetween(50, 100),
        'value_type' => $faker->randomElement(['absolute', 'percentage']),
        'bonus_type_id' => $faker->randomElement([
            'bonus_prime',
            'deposits',
            'first_deposit',
            'free_bet',
            'money_back',
        ]),
        'min_deposit' => $faker->numberBetween(5, 10),
        'max_deposit' => $faker->numberBetween(20, 100),
        'bailout_date' => $faker->dateTimeBetween('+ 10 day', '+ 200 day'),
        'min_odd' => $faker->randomFloat(2, 1, 2),
        'rollover_coefficient' => $faker->numberBetween(5, 10),
        'available_from' => $faker->dateTimeBetween('- 20 day', '- 1 day'),
        'available_until' => $faker->dateTimeBetween('+ 1 day', '+ 20 day'),
        'deadline' => $faker->numberBetween(5, 15),
    ];
});

$factory->define(App\UserBonus::class, function (Faker\Generator $faker) {
    return [
        'promo_code' => str_random(5),
        'balance_bonus' => $faker->numberBetween(10, 200),
        'rollover_amount' => $faker->numberBetween(400, 1000),
        'deadline_date' => $faker->dateTimeBetween('+ 10 day', '+ 200 day'),
        'bonus_value' => $faker->numberBetween(10, 200),
        'bonus_wagered' => $faker->numberBetween(10, 100),
    ];
});

$factory->define(App\UserTransaction::class, function (Faker\Generator $faker) {
    return [
        'origin' => $faker->randomElement([
            'paypal',
            'bank_transfer',
        ]),
       'transaction_id' => str_random(),
       'api_transaction_id' => str_random(),
    ];
});

$factory->define(App\UserStatus::class, function (Faker\Generator $faker) {
    return [];
});

$factory->define(App\UserBetslip::class, function (Faker\Generator $faker) {
    return [];
});

$factory->define(App\UserBet::class, function (Faker\Generator $faker) {
    return [
        'currency' => 'eur',
        'amount' => $faker->numberBetween(2, 10),
        'status' => 'waiting_result',
        'type' => $faker->randomElement(['simple', 'multi']),
        'odd' => $faker->randomFloat(1, 4),
    ];
});

$factory->define(App\UserBetStatus::class, function (Faker\Generator $faker) {
    return [
        'status' => 'waiting_result',
    ];
});

$factory->define(App\UserBetTransaction::class, function (Faker\Generator $faker) {
    return [
        'operation' => 'withdrawal',
    ];
});

$factory->define(App\UserBetEvent::class, function (Faker\Generator $faker) {
    return [
        'status' => 'waiting_result',
        'odd' => $faker->randomFloat(1, 5),
        'event_name' => $faker->word,
        'market_name' => $faker->word,
        'game_name' => $faker->word,
    ];
});

$factory->define(App\Bets\Bets\BetslipBet::class, function (Faker\Generator $faker) {
    return [
        'api_bet_type' => 'betportugal',
        'api_bet_id' => '',
        'api_transaction_id' => '',
    ];
});

$factory->define(App\Bets\Bets\Events\BetslipEvent::class, function (Faker\Generator $faker) {
    return factory(App\UserBetEvent::class)->make()->toArray();
});
