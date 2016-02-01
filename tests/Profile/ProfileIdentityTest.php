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

    public function testProfileDontChangeMorada()
    {
        $user = \App\User::findByUsername('A');
        $this
            ->be($user);
        $this
            ->visit('/perfil')
            ->submitForm('Alterar Info', [
                'profession' => 'Tech',
                'phone' => '123456789'
            ])
            ->seeJsonEquals(['status' => 'success', 'type' => 'reload'])
            ->assertSessionHas('success', 'Perfil alterado com sucesso!');
    }
    public function testProfileChangeMoradaRequireUpload()
    {
        $fake = Faker\Factory::create('pt_PT');
        $user = \App\User::findByUsername('A');
        $this
            ->be($user);
        $this
            ->visit('/perfil')
            ->submitForm('Alterar Info', [
                'city' => $fake->city,
                'zip_code' =>$fake->postcode
            ])
            ->seeJsonEquals(['status' => 'error',
                'msg' => ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']
            ]);
    }
    public function testProfileChangeMoradaWithPdf()
    {
        $fake = Faker\Factory::create('pt_PT');
        $user = \App\User::findByUsername('A');
        $this
            ->be($user);
        $this
            ->visit('/perfil')
            ->submitForm('Alterar Info', [
                'city' => $fake->city,
                'zip_code' =>$fake->postcode,
                'upload' => ''
            ], [
                'upload' => 'C:\\Work\\Projectos\\casino\\tests\\tmp_files\\empty.pdf'
            ])
            ->seeJsonEquals(['status' => 'success', 'type' => 'reload'])
            ->assertSessionHas('success', 'Perfil alterado com sucesso!');
    }
}
