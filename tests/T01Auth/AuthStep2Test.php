<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class T012AuthStep2Test extends TestCase
{
    // use DatabaseTransactions;

    private $email;

    public function setUp()
    {
        parent::setUp();
        // $this->email = 'abc@example.pt';
        $this->email = 'jmiguelcouto@gmail.com';
    }
    /**
     * Accessing Step 2 redirect to step 1.
     *
     * @return void
     */
    public function testRedirectToStep1()
    {
        $this->visit('/registar/step2')
            ->seePageIs('/registar/step1');
    }

    /**
     * Clear Test info
     *
     * @return void
     */
    public function testClear()
    {
        DB::delete('delete from `list_identity_checks` where name = ?', ['Miguel']);
        DB::delete('delete from `users` where username = ?', ['A']);
        DB::delete('delete s from user_balances s where user_id not in (select id from users u)');
        DB::delete('delete s from user_bank_accounts s where user_id not in (select id from users u)');
        DB::delete('delete s from user_bets s where user_id not in (select id from users u)');
        DB::delete('delete s from user_documentation s where user_id not in (select id from users u)');
        DB::delete('delete s from user_limits s where user_id not in (select id from users u)');
        DB::delete('delete s from user_profiles s where user_id not in (select id from users u)');
        DB::delete('delete s from user_self_exclusions s where user_id not in (select id from users u)');
        DB::delete('delete s from user_sessions s where user_id not in (select id from users u)');
        DB::delete('delete s from user_settings s where user_id not in (select id from users u)');
        DB::delete('delete s from user_statuses s where user_id not in (select id from users u)');
        DB::delete('delete s from user_transactions s where user_id not in (select id from users u)');
    }
    /**
     * Test filling the Form of Step 2.
     *
     * @return void
     */
    public function testFillForm()
    {
        $nif = strval(Faker\Provider\Base::randomNumber(9));

        while (strlen($nif) < 9) $nif = $nif . Faker\Provider\Base::randomDigit();
        // print_r($nif);

        $idCheck = new \App\ListIdentityCheck();
        $idCheck->name = "Miguel";
        $idCheck->tax_number = $nif;
        $idCheck->birth_date = '1980-01-01';
        $idCheck->deceased = 0;
        $idCheck->under_age = 0;
        $idCheck->save();

        //$this->assertEquals($results->)
        $this
            ->withSession([
                'inputs'=>[
                    'gender' => 'm',
                    'name' => 'Miguel',
                    'nationality' => 'PT',
                    'country' => 'pt',
                    'document_number' => $nif,
                    'tax_number' => $nif,
                    'profession' => 'Tech',
                    'address' => 'Rua X',
                    'city' => 'Fama',
                    'zip_code' => '1234',
                    'email' => $this->email,
                    'conf_email' => $this->email,
                    'phone' => '123456789',
                    'username' => 'A',
                    'password' => '123456',
                    'conf_password' => '123456',
                    'security_pin' => '1234',
                    'birth_date' => '1980-01-01',
                    'promo_code' => '',
                    'currency' => 'euro'
                ]
            ])
            ->visit('/registar/step2')
            ->seePageIs('/registar/step2')
            ->assertViewMissing("identity");
    }
}
