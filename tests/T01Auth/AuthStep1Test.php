<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class T011AuthStep1Test extends TestCase
{
    protected $serverVariables = [
        'HTTP_X-Requested-With' => 'XMLHttpRequest'
    ];

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
        $db = DB::connection('docs_db')->getDatabaseName();

        DB::delete('delete from `list_identity_checks` where name = ?', ['Miguel']);
        DB::delete('delete from `user_balances` where user_id = ?', [$id]);
        DB::delete('delete from `user_bet_events` where user_bet_id in (select id from `user_bets` where user_id = ?)', [$id]);
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
        DB::delete('delete from `messages` where user_id = ?', [$id]);
        DB::delete('delete from `user_sessions` where user_id = ?', [$id]);
        DB::delete('delete from '.$db.'.`user_document_attachments` where user_id = ?', [$id]);
        DB::delete('delete from `users` where id = ?', [$id]);
    }

    function getValidNif() {
        $nif = strval(Faker\Provider\Base::randomNumber(8));

        while (strlen($nif) < 8) $nif = $nif . Faker\Provider\Base::randomDigit();

        $nifSplit=str_split($nif);
        //Calculamos o dígito de controlo
        $checkDigit=0;
        for($i=0; $i<8; $i++) {
            $checkDigit+=$nifSplit[$i]*(10-$i-1);
        }
        $checkDigit=11-($checkDigit % 11);
        //Se der 10 então o dígito de controlo tem de ser 0
        if($checkDigit>=10) $checkDigit=0;
        return $nif . $checkDigit;
    }
    /**
     * Test filling the Form of Step 1.
     *
     * @return void
     */
    public function testFillForm()
    {
        $nif = $this->getValidNif();

        $idCheck = new \App\ListIdentityCheck();
        $idCheck->name = "Couto";
        $idCheck->tax_number = $nif;
        $idCheck->birth_date = '1980-01-01';
        $idCheck->deceased = 0;
        $idCheck->under_age = 0;
        $idCheck->save();

        $ab = $this->visit('/registar/step1');
        $this->session([
            'captcha.code' => 'ABCDE'
        ]);
        $ab
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
            ->type('+351 123456789', 'phone')
            ->type('A', 'username')
            ->type('123456', 'password')
            ->type('123456', 'conf_password')
            ->type('1234', 'security_pin')
            ->type('ABCDE', 'captcha')
            ->check('general_conditions')
            ->press('VALIDAR')
            ->seeJsonEquals([
                'msg' => 'Dados guardados com sucesso!',
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
        $nif = $this->getValidNif();

        $idCheck = new \App\ListIdentityCheck();
        $idCheck->name = "Couto";
        $idCheck->tax_number = $nif;
        $idCheck->birth_date = '1980-01-01';
        $idCheck->deceased = 0;
        $idCheck->under_age = 0;
        $idCheck->save();

        $ab = $this->visit('/registar/step1');
        $this->session([
            'captcha.code' => 'ABCDE'
        ]);
        $ab
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
            ->type('+351123456789', 'phone')
            ->type('B', 'username')
            ->type('123456', 'password')
            ->type('123456', 'conf_password')
            ->type('1234', 'security_pin')
            ->type('ABCDE', 'captcha')
            ->check('general_conditions')
            ->press('VALIDAR')
            ->seeJsonEquals([
                'msg' => 'Dados guardados com sucesso!',
                'redirect' => "/registar/step2",
                "status" => "success",
                "type" => "redirect",
            ]);
    }
}
