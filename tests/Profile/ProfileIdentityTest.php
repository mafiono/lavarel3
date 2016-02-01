<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProfileIdentityTest extends TestCase
{
    /**
     * Test filling the Form of Step 1.
     *
     * @return void
     */
    public function testLoggedOutUser()
    {
        $this->visit('/perfil')
            ->seePageIs('/');
    }
    public function testLogin()
    {
        Session::start();
        $this->post('/login', [
            'username' => 'A',
            'password' => '123456',
            '_token' => csrf_token()
        ])->seeJsonEquals(array('status' => 'success', 'type' => 'reload'));
        Session::flush();
    }

    public function testWithLoggedUser()
    {
        $user = \App\User::findByUsername('A');
        $this
            ->be($user);
        $this
            ->visit('/perfil')
            ->seePageIs('/perfil');
    }

}
