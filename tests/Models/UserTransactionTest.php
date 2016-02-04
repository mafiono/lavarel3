<?php
use App\User;
use App\UserSession;
use App\UserTransaction;
use Carbon\Carbon;

/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 04/02/2016
 * Time: 10:04
 */
class UserTransactionTest extends TestCase
{
    /**
     * Generate Hash
     *
     * @return string Hash
     */
    public function generateHash()
    {
        $user = User::findByUsername('A');
        $id = $user->id;
        $date = Carbon::now();
        $hash = UserTransaction::getHash($id, $date);
        return $hash;
    }

    /**
     * Test Create a Transaction
     */
    public function testCreateTransaction(){
        $hash = $this->generateHash();
        $user = User::findByUsername('A');
        $id = $user->id;
        $sessionId = UserSession::query()->where('user_id', '=', $id)->max('id');
        $trans = UserTransaction::createTransaction(10, $id, 'paypal', 'deposit', null, $sessionId);
        $this->assertNotNull($trans, "Invalid Return!");
        $this->assertNotEquals(false, $trans, "Failed to Save");
        $this->assertEquals($hash, $trans->transaction_id);
    }
}
