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
     * Test filling the Form of Step 2.
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

        $result = $this
            ->withSession([
                'inputs'=>[
                    'gender' => 'm',
                    'firstname' => 'Miguel',
                    'name' => 'Couto',
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
                    'phone' => '+351 123456789',
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
        //$result->assertViewMissing('identity');
        $result->assertViewMissing('error');
    }
    public function testRegistFriend()
    {
        $nif = strval(Faker\Provider\Base::randomNumber(9));

        while (strlen($nif) < 9) $nif = $nif . Faker\Provider\Base::randomDigit();
        // print_r($nif);

        $idCheck = new \App\ListIdentityCheck();
        $idCheck->name = "Couto2";
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
                    'firstname' => 'Miguel2',
                    'name' => 'Couto2',
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
}
