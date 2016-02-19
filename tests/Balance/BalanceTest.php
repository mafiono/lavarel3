<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 19/02/2016
 * Time: 18:05
 */

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BalanceTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        // all tests need to have user logged in.
        $user = \App\User::findByUsername('A');
        $this
            ->be($user);
    }


    public function testWithdraw()
    {
        $this->post('/banco/levantar', [
            'bank_account' => '2',
            'withdrawal_value' => '3',
            'password' => '123456'
        ])
            ->assertSessionHas('error', 'Não possuí saldo suficiente para o levantamento pedido.');
            //->assertSessionHas('success', 'Pedido de levantamento efetuado com sucesso!');

    }
}