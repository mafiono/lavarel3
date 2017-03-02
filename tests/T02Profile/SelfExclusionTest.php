<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 29/02/2016
 * Time: 10:36
 */

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class T022SelfExclusionTest extends TestCase
{
    public function testFixStatus()
    {
        $user = \App\User::findByUsername('A');

        $user->status->identity_status_id = 'confirmed';
        $user->status->selfexclusion_status_id = null;
        $user->status->status_id = 'approved';
        $user->status->save();
    }

    public function testMinimumExclusion(){
        $this->startSession();
        $user = \App\User::findByUsername('A');
        $this->be($user);

        $this->post('/jogo-responsavel/autoexclusao', [
            'dias' => 95,
            'motive' => 'Cansado do Jogo.',
            'self_exclusion_type' => 'minimum_period',
            '_token' => csrf_token()
        ])
            ->seeJsonContains([
                'status' => 'success'
            ])
            ->assertSessionHas('success', 'Pedido de autoexclusão efetuado com sucesso!');


    }
    public function testMinimumExclusionFail(){
        $this->startSession();
        $user = \App\User::findByUsername('A');
        $this->be($user);

        $this->post('/jogo-responsavel/autoexclusao', [
            'dias' => 30,
            'self_exclusion_type' => 'minimum_period',
            '_token' => csrf_token()
        ])->seeJsonContains([
            'status' => 'error'
        ]);
    }
}