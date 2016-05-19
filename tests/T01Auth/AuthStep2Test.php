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
        $this->email = env('TEST_MAIL');
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
        $this->clearUser('A');
        $this->clearUser('B');
    }

    private function clearUser($username)
    {
        $key = DB::table('users')->where('username', '=', $username)->first(['id']);
        if ($key == null) return;
        $id = $key->id;

        DB::delete('delete from `list_identity_checks` where name = ?', ['Miguel']);
        DB::delete('delete from `user_balances` where user_id = ?', [$id]);
        DB::delete('delete from `user_bets` where user_id = ?', [$id]);
        DB::delete('delete from `user_documentation` where user_id = ?', [$id]);
        DB::delete('delete from `user_limits` where user_id = ?', [$id]);
        DB::delete('delete from `user_invites` where user_id = ?', [$id]);
        DB::delete('delete from `user_profiles` where user_id = ?', [$id]);
        DB::delete('delete from `user_profiles_log` where user_id = ?', [$id]);
        DB::delete('delete from `user_self_exclusions` where user_id = ?', [$id]);
        DB::delete('delete from `user_settings` where user_id = ?', [$id]);
        DB::delete('delete from `user_statuses` where user_id = ?', [$id]);
        DB::delete('delete from `user_transactions` where user_id = ?', [$id]);
        DB::delete('delete from `user_bank_accounts` where user_id = ?', [$id]);
        DB::delete('delete from `user_sessions` where user_id = ?', [$id]);
        DB::delete('delete from `users` where id = ?', [$id]);
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

        $result = $this
            ->withSession([
                'inputs'=>[
                    'gender' => 'm',
                    'name' => 'Miguel',
                    'nationality' => 'PT',
                    'country' => 'PT',
                    'document_number' => $nif,
                    'tax_number' => $nif,
                    'sitprofession' => '22',
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
            ->seePageIs('/registar/step2');
        $result->assertViewMissing('identity');
        $result->assertViewMissing('error');
    }
    public function testRegistFriend()
    {
        $nif = strval(Faker\Provider\Base::randomNumber(9));

        while (strlen($nif) < 9) $nif = $nif . Faker\Provider\Base::randomDigit();
        // print_r($nif);

        $idCheck = new \App\ListIdentityCheck();
        $idCheck->name = "Miguel2";
        $idCheck->tax_number = $nif;
        $idCheck->birth_date = '1980-01-01';
        $idCheck->deceased = 0;
        $idCheck->under_age = 0;
        $idCheck->save();

        $promo_code = DB::table('users')->where('username', '=', 'A')->first(['user_code'])->user_code;

        $result = $this
            ->withSession([
                'inputs'=>[
                    'gender' => 'm',
                    'name' => 'Miguel2',
                    'nationality' => 'PT',
                    'country' => 'PT',
                    'document_number' => $nif,
                    'tax_number' => $nif,
                    'sitprofession' => '22',
                    'profession' => 'Tech',
                    'address' => 'Rua X',
                    'city' => 'Fama',
                    'zip_code' => '1234',
                    'email' => 'x'.$this->email,
                    'conf_email' => 'x'.$this->email,
                    'phone' => '123456789',
                    'username' => 'B',
                    'password' => '123456',
                    'conf_password' => '123456',
                    'security_pin' => '1234',
                    'birth_date' => '1980-01-01',
                    'promo_code' => $promo_code,
                    'currency' => 'euro'
                ]
            ])
            ->visit('/registar/step2')
            ->seePageIs('/registar/step2');
        $result->assertViewMissing('identity');
        $result->assertViewMissing('error');
    }
}
