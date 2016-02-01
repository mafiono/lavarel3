<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthZConfirmPassTest extends TestCase
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
     * Clear Test info
     *
     * @return void
     */
    public function testClear()
    {
        DB::update('update `user_profiles` set email_token = ?, email_checked = 0 where email = ?', [ '123456789', $this->email ]);
    }
    /**
     * Test Confirm with Empty Values
     *
     * @return void
     */
    public function testEmptyGetRedirectToHome()
    {
        $this
            ->visit('/confirmar_email')
            ->seePageIs('/');
    }

    /**
     * Test Confirm with Empty Values
     *
     * @return void
     */
    public function testComfirmEmail()
    {
        $testUrl = '/confirmar_email?email='.$this->email.'&token=123456789';
        $this
            ->get($testUrl)
            ->assertRedirectedTo('/email_confirmado');
    }
}
