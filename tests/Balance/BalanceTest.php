<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 19/02/2016
 * Time: 18:05
 */

use App\User;
use App\UserBankAccount;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class BalanceTest
 *
 * @property User $user
 * @property UserBankAccount $bankAccount
 */
class BalanceTest extends TestCase
{
    private $user;
    private $userSessionId;
    private static $bankAccount;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        Session::start();
        // all tests need to have user logged in.
        $this->user = User::findByUsername('A');
        $this->be($this->user);

        $userSession = $this->user->createUserSession(['description' => 'test'], true);
        $this->assertNotFalse($userSession);
        Session::put('user_session', $userSession->id);
        $this->userSessionId = $userSession->id;
    }

    public function testDepositDirect()
    {
        $depositValue = 50;
        /* @var $trans \App\UserTransaction */
        $trans = $this->user->newDeposit($depositValue, 'paypal', $this->userSessionId);
        $this->assertNotFalse($trans);

        $transId = $trans->transaction_id;
        $payment_id = "test".str_random(10);

        $trans = $this->user->updateTransaction($transId, $depositValue, 'processed', $this->userSessionId, $payment_id);
        $this->assertNotFalse($trans);
    }

    public function testAddBankWithPdf()
    {
        $fake = Faker\Factory::create('pt_PT');
        $iban = substr($fake->bankAccountNumber(), 4);
        $resp = $this
            ->call('PUT', '/banco/conta-pagamentos', [
                'bank' => 'BIC',
                'iban' => $iban,
                'upload' => '.\\tests\\tmp_files\\empty.pdf',
                '_token' => csrf_token()
            ], [], [
                'upload' => new UploadedFile('.\\tests\\tmp_files\\empty.pdf', 'empty.pdf')
            ]);
        $this->assertEquals(302, $resp->getStatusCode());

        $iban = 'PT50'.$iban;
        /* @var $ba UserBankAccount */
        $ba = UserBankAccount::query()->where('iban', '=', $iban)->first();

        $ba->status_id = 'in_use';
        $this->assertTrue($ba->save());
        self::$bankAccount = $ba;
    }

    public function testWithdraw()
    {
        $this->post('/banco/levantar', [
            'bank_account' => '2',
            'withdrawal_value' => '60',
            'password' => '123456',
            '_token' => csrf_token()
        ])
            ->assertSessionHas('error', 'Não possuí saldo suficiente para o levantamento pedido.');

        $this->post('/banco/levantar', [
            'bank_account' => '2',
            'withdrawal_value' => '20',
            'password' => '123456',
            '_token' => csrf_token()
        ])
            ->assertSessionHas('error', 'Escolha uma conta bancária válida.');

        $id = self::$bankAccount->id;
        $this->post('/banco/levantar', [
            'bank_account' => $id,
            'withdrawal_value' => '13',
            'password' => '123456',
            '_token' => csrf_token()
        ])
            ->assertSessionHas('success', 'Pedido de levantamento efetuado com sucesso!');
    }
}