<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class T011AuthStep1Test extends TestCase
{
    /*
    delete s from user_balances s where user_id not in (select id from users u);
    delete s from user_bank_accounts s where user_id not in (select id from users u);
    delete s from user_bets s where user_id not in (select id from users u);
    delete s from user_documentation s where user_id not in (select id from users u);
    delete s from user_limits s where user_id not in (select id from users u);
    delete s from user_profiles s where user_id not in (select id from users u);
    delete s from user_self_exclusions s where user_id not in (select id from users u);
    delete s from user_sessions s where user_id not in (select id from users u);
    delete s from user_settings s where user_id not in (select id from users u);
    delete s from user_statuses s where user_id not in (select id from users u);
    delete s from user_transactions s where user_id not in (select id from users u);
    */

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->visit('/')
            ->see('JOGOS')
            ->dontSee('Rails');
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
        DB::delete('delete from `user_transactions` where user_id = ?', [$id]);
        DB::delete('delete from `user_bank_accounts` where user_id = ?', [$id]);
        DB::delete('delete from `user_documentation` where user_id = ?', [$id]);
        DB::delete('delete from `user_limits` where user_id = ?', [$id]);
        DB::delete('delete from `user_invites` where user_id = ?', [$id]);
        DB::delete('delete from `user_profiles` where user_id = ?', [$id]);
        DB::delete('delete from `user_profiles_log` where user_id = ?', [$id]);
        DB::delete('delete from `user_revocations` where user_id = ?', [$id]);
        DB::delete('delete from `user_self_exclusions` where user_id = ?', [$id]);
        DB::delete('delete from `user_settings` where user_id = ?', [$id]);
        DB::delete('delete from `user_statuses` where user_id = ?', [$id]);
        DB::delete('delete from `user_complains` where user_id = ?', [$id]);
        DB::delete('delete from `user_sessions` where user_id = ?', [$id]);
        DB::delete('delete from `users` where id = ?', [$id]);
    }
    /**
     * Test filling the Form of Step 1.
     *
     * @return void
     */
    public function testFillForm()
    {
        $nif = strval(Faker\Provider\Base::randomNumber(9));

        while (strlen($nif) < 9) $nif = $nif . Faker\Provider\Base::randomDigit();
        // print_r($nif);

        $idCheck = new \App\ListIdentityCheck();
        $idCheck->name = "Couto";
        $idCheck->tax_number = $nif;
        $idCheck->birth_date = '1980-01-01';
        $idCheck->deceased = 0;
        $idCheck->under_age = 0;
        $idCheck->save();

        $this->visit('/registar/step1')
            ->type('m', 'gender')
            ->type('Miguel', 'firstname')
            ->type('Couto', 'name')
            ->select('PT', 'nationality')
            ->select('PT', 'country')
            ->type($nif, 'document_number')
            ->type($nif, 'tax_number')
            ->select('22', 'sitprofession')
            //->type('Tech', 'profession')
            ->type('Rua X', 'address')
            ->type('Fama', 'city')
            ->type('1234', 'zip_code')
            ->type(env('TEST_MAIL'), 'email')
            ->type(env('TEST_MAIL'), 'conf_email')
            ->type('123456789', 'phone')
            ->type('A', 'username')
            ->type('123456', 'password')
            ->type('123456', 'conf_password')
            ->type('1234', 'security_pin')
            ->check('general_conditions')
            ->press('VALIDAR')
            ->seeJsonEquals([
                'redirect' => "/registar/step2",
                "status" => "success",
                "type" => "redirect",
            ]);
    }

    /**
     * Test filling the Form of Step 1.
     *
     * @return void
     */
    public function testFormFriend()
    {
        $nif = strval(Faker\Provider\Base::randomNumber(9));

        while (strlen($nif) < 9) $nif = $nif . Faker\Provider\Base::randomDigit();
        // print_r($nif);

        $idCheck = new \App\ListIdentityCheck();
        $idCheck->name = "Couto";
        $idCheck->tax_number = $nif;
        $idCheck->birth_date = '1980-01-01';
        $idCheck->deceased = 0;
        $idCheck->under_age = 0;
        $idCheck->save();

        $this->visit('/registar/step1')
            ->type('m', 'gender')
            ->type('Miguel2', 'firstname')
            ->type('Couto2', 'name')
            ->select('PT', 'nationality')
            ->select('PT', 'country')
            ->type($nif, 'document_number')
            ->type($nif, 'tax_number')
            ->select('22', 'sitprofession')
            //->type('Tech', 'profession')
            ->type('Rua X', 'address')
            ->type('Fama', 'city')
            ->type('1234', 'zip_code')
            ->type('x'.env('TEST_MAIL'), 'email')
            ->type('x'.env('TEST_MAIL'), 'conf_email')
            ->type('123456789', 'phone')
            ->type('B', 'username')
            ->type('123456', 'password')
            ->type('123456', 'conf_password')
            ->type('1234', 'security_pin')
            ->check('general_conditions')
            ->press('VALIDAR')
            ->seeJsonEquals([
                'redirect' => "/registar/step2",
                "status" => "success",
                "type" => "redirect",
            ]);
    }
}
