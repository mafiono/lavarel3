<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class T021ProfileIdentityTest extends TestCase
{
    private function getLastSession(){
        $data = DB::table('user_sessions')
            ->join('users', 'users.id', '=', 'user_sessions.user_id')
            ->where('users.username', '=', 'A')
            ->select(DB::raw('max(`user_sessions`.`id`) as `userSessionId`'.
                ', max(`user_sessions`.`session_number`) as `user_session_number`'))
            ->first();

//        $id = DB::select('select max(s.id) as id from user_sessions s '.
//            'left outer join users u on u.id = s.user_id where u.username = ? ', ['A']);

        return (array) $data;
    }
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
        $this->startSession();
        $this->post('/login', [
            'username' => 'A',
            'password' => '123456',
            '_token' => csrf_token()
        ])
            ->seeJsonEquals(['status' => 'success', 'type' => 'reload']);

        $this->flushSession();
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

    public function testDontChangeMorada()
    {
        $user = \App\User::findByUsername('A');
        $this
            ->be($user);
        $this
            ->visit('/perfil')
            ->submitForm('Guardar', [
                'profession' => 'Tech',
                'phone' => '123456789'
            ])
            ->seeJsonEquals(['status' => 'success', 'type' => 'reload'])
            ->assertSessionHas('success', 'Perfil alterado com sucesso!');
    }
    public function testChangeMoradaWithUpload()
    {
        $fake = Faker\Factory::create('pt_PT');
        $user = \App\User::findByUsername('A');
        $this
            ->be($user);
        $this
            ->visit('/perfil')
            ->submitForm('Alterar Info.', [
                'city' => $fake->city,
                'zip_code' =>$fake->postcode
            ])
            ->seeJsonEquals(['status' => 'error',
                'msg' => ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']
            ]);
    }

    public function testChangeMoradaWithPdf()
    {
        $fake = Faker\Factory::create('pt_PT');
        $user = \App\User::findByUsername('A');
        $this
            ->be($user);
        $this
            ->withSession($this->getLastSession())
            ->visit('/perfil')
            ->submitForm('Alterar Info.', [
                'city' => $fake->city,
                'zip_code' =>$fake->postcode,
                'upload' => '.\\tests\\tmp_files\\empty.pdf'
            ], [
                'upload' => 'empty.pdf'
            ])
            ->seeJsonEquals(['status' => 'success', 'type' => 'reload'])
            ->assertSessionHas('success', 'Perfil alterado com sucesso!');
    }
}
