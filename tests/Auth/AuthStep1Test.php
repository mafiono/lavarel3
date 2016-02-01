<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthStep1Test extends TestCase
{
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
     * Test filling the Form of Step 1.
     *
     * @return void
     */
    public function testFillForm()
    {
        $nif = strval(Faker\Provider\Base::randomNumber(9));

        while (strlen($nif) < 9) $nif = $nif . Faker\Provider\Base::randomDigit();
        // print_r($nif);

        $this->visit('/registar/step1')
            ->type('m', 'gender')
            ->type('Miguel', 'name')
            ->type('PT', 'nationality')
            ->type($nif, 'document_number')
            ->type('123456789', 'tax_number')
            ->type('Tech', 'profession')
            ->type('Rua X', 'address')
            ->type('Fama', 'city')
            ->type('1234', 'zip_code')
            ->type('abc@example.pt', 'email')
            ->type('abc@example.pt', 'conf_email')
            ->type('123456789', 'phone')
            ->type('B', 'username')
            ->type('123456', 'password')
            ->type('123456', 'conf_password')
            ->type('123456', 'security_pin')
            ->check('general_conditions')
            ->press('Continuar')
            ->seeJsonEquals([
                'redirect' => "/registar/step2",
                "status" => "success",
                "type" => "redirect",
            ]);


    }
}
