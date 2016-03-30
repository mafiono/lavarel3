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
    use DatabaseTransactions;

    private $user;

    public function setUp()
    {
        parent::setUp();

        $this->startSession();
        $this->user = \App\User::findByUsername('A');
        $this->be($this->user);
    }

    public function testMinimumExclusion(){
        $this->post('/jogo-responsavel/autoexclusao', [
            'dias' => 95,
            'motive' => 'Cansado do Jogo.',
            'self_exclusion_type' => 'minimum_period',
            '_token' => csrf_token()
        ])->assertSessionHas('success', 'Pedido de auto-exclusão efetuado com sucesso!');


    }
    public function testMinimumExclusionFail(){
        $this->post('/jogo-responsavel/autoexclusao', [
            'dias' => 30,
            'self_exclusion_type' => 'minimum_period',
            '_token' => csrf_token()
        ])->seeJsonContains([
            'status' => 'error'
        ]);
    }
}