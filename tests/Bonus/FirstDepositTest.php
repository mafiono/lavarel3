<?php

use Carbon\Carbon;

class FirstDepositTest extends BaseBonusTest
{
    protected $deadline = 10;

    protected $depositAmount = 100;

    public function setUp()
    {
        parent::setUp();

        $this->user = $this->createUserWithEverything([
            App\User::class => [
                'rating_risk' => 'Risk0',
                'rating_group' => 'Walk',
                'rating_type' => 'Mouse',
                'rating_class' => 'Test',
            ],
            App\UserTransaction::class => [
                'status_id' => 'processed',
                'debit' => '100',
                'origin' => 'bank_transfer'
            ],
            App\UserStatus::class => [
                'status_id' => 'approved',
                'identity_status_id' => 'confirmed',
                'email_status_id' => 'confirmed',
                'iban_status_id' => 'confirmed',
                'address_status_id' => 'confirmed',
            ]
        ]);

        $this->bonus = $this->createBonus([
            'bonus_type_id' => 'first_deposit',
            'min_odd' => 2.2,
            'value_type' => 'percentage',
            'deadline' => $this->deadline,
            'rollover_coefficient' => 0,
            'value' => 10,
            'max_bonus' => 100,
        ]);

        $this->bonus->depositMethods()->create([
            'deposit_method_id' => 'bank_transfer'
        ]);

        $this->bonus->targets()->create([
            'target_id' => 'Risk0'
        ]);

        $this->deposit = $this->user->transactions->last();

        $this->user->balance->addAvailableBalance(100);

        auth()->login($this->user->fresh());
    }

    public function testItIsAvailableWithOneDeposit()
    {
        $this->assertBonusAvailable();
    }

    public function testItIsUnavailableWhenNotBetweenAvailableInterval()
    {
        // After available interval
        $availableUntil = $this->bonus->available_until;
        $this->bonus->available_until = Carbon::now()->subDay(1);
        $this->bonus->save();

        $this->assertBonusNotAvailable();

        $this->bonus->available_until = $availableUntil;
        $this->bonus->save();

        // Before available interval

        $this->bonus->available_from;
        $this->bonus->available_from = Carbon::now()->addDay(1);
        $this->bonus->save();

        $this->assertBonusNotAvailable();
    }

    public function testItIsUnavailableAfterRedeem()
    {
        SportsBonus::redeem($this->bonus->id);

        $this->assertBonusNotAvailable();
    }

    public function testItIsUnavailableIfItIsAnOldVersion()
    {
        $this->bonus->current = false;
        $this->bonus->save();

        $this->assertBonusNotAvailable();
    }

    public function testItIsUnavailableWithoutUserTransactions()
    {
        $this->user->transactions()->delete();

        $this->assertBonusNotAvailable();
    }

    public function testItHasCorrectAttributesAfterRedeem()
    {
        SportsBonus::redeem($this->bonus->id);

        $this->user->balance = $this->user->balance->fresh();

        $this->seeInDatabase('user_bonus', [
            'user_id' => $this->user->id,
            'bonus_id' => $this->bonus->id,
            'bonus_head_id' => $this->bonus->head_id,
            'user_transaction_id' => $this->deposit->id,
            'active' => 1,
            'deposited' => 1,
            'bonus_value' => $this->depositAmount * 0.1,
            'rollover_amount' => 0,
            'deadline_date' => SportsBonus::getActive()->created_at->addDays($this->deadline),
        ]);
    }

    public function testItIsActiveAfterRedeem()
    {
        SportsBonus::redeem($this->bonus->id);

        $this->assertHasActiveBonus();
    }

    public function testRedeemFailsWithInvalidBonusId()
    {
        $this->setExpectedException(App\Bonus\Sports\SportsBonusException::class);

        SportsBonus::redeem('invalidId');

        $this->assertHasNoActiveBonus();
    }

    public function testRedeemFailsAfterItHasBeenRedeemed()
    {
        SportsBonus::redeem($this->bonus->id);

        $this->setExpectedException(App\Bonus\Sports\SportsBonusException::class);

        SportsBonus::redeem($this->bonus->id);

        $this->assertHasActiveBonus();
    }

    public function testItCanBeCancelledAfterRedeemed()
    {
        SportsBonus::redeem($this->bonus->id);

        SportsBonus::cancel();

        $this->assertHasNoActiveBonus();
    }

    public function testItCanNotBeRedeemedAfterCancelled()
    {
        SportsBonus::redeem($this->bonus->id);

        SportsBonus::cancel();

        $this->setExpectedException(App\Bonus\Sports\SportsBonusException::class);

        SportsBonus::redeem($this->bonus->id);

        $this->assertHasNoActiveBonus();
    }

    public function testThatCancelFailsAfterItHasBeenCancelled()
    {
        SportsBonus::redeem($this->bonus->id);

        SportsBonus::cancel();

        $this->setExpectedException(App\Bonus\Sports\SportsBonusException::class);

        SportsBonus::cancel();
    }

    public function testCancelFailsIfUserIsSelfExcluded()
    {
        SportsBonus::redeem($this->bonus->id);

        $this->user->status->update([
            'selfexclusion_status_id' => 'minimum_period',
        ]);

        $this->setExpectedException(App\Bonus\Sports\SportsBonusException::class);

        SportsBonus::refreshUser();

        SportsBonus::cancel();
    }

    public function testItIsConsumedAfterCancelAndNotBefore()
    {
        SportsBonus::redeem($this->bonus->id);

        $this->assertBonusWasNotConsumed();

        SportsBonus::cancel();

        $this->assertBonusWasConsumed();
    }

    public function testRedeemShouldFailWhenAnotherBonusIsActive()
    {
        SportsBonus::redeem($this->bonus->id);

        $this->setExpectedException(App\Bonus\Sports\SportsBonusException::class);

        $newBonus = $this->createBonus([
            'bonus_type_id' => 'first_deposit',
            'min_odd' => 1.2,
            'value_type' => 'percentage',
            'deadline' => 4,
            'rollover_coefficient' => 2,
            'value' => 100,
        ]);

        SportsBonus::redeem($newBonus->id);
    }

    public function testTransactionDepositInferiorToMinDepositAwardsNoBonus()
    {
        $trans = $this->user->transactions->last();
        $trans->debit = 10;
        $trans->save();

        $this->bonus->update([
            'min_deposit' => 20,
        ]);

        $this->assertBonusNotAvailable($this->bonus->id);
    }

    public function testItAwardsTheCorrectBonusAmount()
    {
        SportsBonus::redeem($this->bonus->id);

        $this->assertBonusOfUser($this->user, 10);
    }

    public function testThatTheAmountOfBonusAfterCancelIsZero()
    {
        SportsBonus::redeem($this->bonus->id);

        SportsBonus::cancel();

        $this->assertBonusOfUser($this->user, 0);
    }

    public function testItAutoCancelsAfterDeadlineDate()
    {
        SportsBonus::redeem($this->bonus->id);

        SportsBonus::userBonus()->update([
            'deadline_date' => Carbon::now()->subHour(2)
        ]);

        Artisan::call('cancel-bonuses');

        SportsBonus::swapUser($this->user);

        $this->assertBonusWasConsumed($this->bonus->id);
    }

    public function testIfUserMakesWithdrawalWithoutUnresolvedBetsThenBonusIsCancelled()
    {
        SportsBonus::redeem($this->bonus->id);

        $this->createWithdrawalFromUserAccount($this->user->id, 25);

        $this->assertBonusWasConsumed();
    }

    public function testBonusDepositIsNotGreaterThanMaxBonus()
    {
        $this->bonus->max_bonus = 169;
        $this->bonus->save();

        $trans = $this->user->transactions->last();
        $trans->debit = 10000;
        $trans->save();

        SportsBonus::redeem($this->bonus->id);

        $this->assertBonusOfUser($this->user, $this->bonus->max_bonus);
    }

    public function testItCreatesUserTransactionWhenRedeemed()
    {
        SportsBonus::redeem($this->bonus->id);

        $balance = $this->user->balance;

        $this->seeInDatabase('user_transactions', [
            'user_id' => $this->user->id,
            'origin' => 'sport_bonus',
            'debit_bonus' => number_format(10, 2),
            'initial_balance' => number_format($balance->balance_available, 2),
            'final_balance' => number_format($balance->balance_available, 2),
            'initial_bonus' => number_format($balance->balance_bonus, 2),
            'final_bonus' => number_format($balance->balance_bonus + 10, 2),
            'description' => 'Resgate de bónus ' . $this->bonus->title,
        ]);
    }

    public function testItCreatesUserTransactionWhenEnds()
    {
        SportsBonus::redeem($this->bonus->id);

        SportsBonus::cancel($this->bonus->id);

        $balance = $this->user->balance;

        $this->seeInDatabase('user_transactions', [
            'user_id' => $this->user->id,
            'origin' => 'sport_bonus',
            'credit_bonus' => number_format(10, 2),
            'initial_balance' => number_format($balance->balance_available, 2),
            'final_balance' => number_format($balance->balance_available, 2),
            'initial_bonus' => number_format(10, 2),
            'final_bonus' => number_format(0, 2),
            'description' => 'Término de bónus ' . $this->bonus->title,
        ]);
    }

    public function testItCanNotBeRedeemedWithoutDepositMethodsSelected()
    {
        $this->bonus->depositMethods()->delete();

        $this->assertBonusNotAvailable();
    }

    public function testItCanNotBeRedeemedIfDepositDifferentFromSelected()
    {
        $this->user->transactions()->update(['origin' => 'meo_wallet']);

        $this->assertBonusNotAvailable();
    }

    public function testItCanBeRedeemedByAllDepositsIfTheyAreSelected()
    {
        $this->bonus->depositMethods()->createMany([
            ['deposit_method_id' => 'bank_transfer'],
            ['deposit_method_id' => 'cc'],
            ['deposit_method_id' => 'mb'],
            ['deposit_method_id' => 'meo_wallet'],
            ['deposit_method_id' => 'paypal'],
        ]);

        $this->user->transactions()->update(['origin' => 'cc']);
        $this->assertBonusAvailable();

        $this->user->transactions()->update(['origin' => 'mb']);
        $this->assertBonusAvailable();

        $this->user->transactions()->update(['origin' => 'meo_wallet']);
        $this->assertBonusAvailable();

        $this->user->transactions()->update(['origin' => 'paypal']);
        $this->assertBonusAvailable();
    }

    public function testItIsNotAvailableWithMoreThan1Deposit()
    {
        $this->user->transactions()->create([
            'status_id' => 'processed',
            'debit' => '100',
            'origin' => 'bank_transfer'
        ]);

        $this->assertBonusNotAvailable();
    }

    public function testItIsAvailableIfUserPlacedBets()
    {
        $this->placeBetForUser($this->user->id, 10, 3.5, [], 3);

        $this->assertBonusAvailable();
    }

    public function testItCanHaveMoreThatOneBonusAvailableForSameDeposit()
    {
        $newBonus = $this->createBonus([
            'bonus_type_id' => 'first_deposit',
            'min_odd' => 1.2,
            'value_type' => 'percentage',
            'deadline' => 4,
            'rollover_coefficient' => 2,
            'value' => 100,
        ]);

        $newBonus->depositMethods()->create([
            'deposit_method_id' => 'bank_transfer'
        ]);

        $newBonus->targets()->create([
            'target_id' => 'Risk0'
        ]);

        $this->assertBonusAvailable($this->bonus->id);

        $this->assertBonusAvailable($newBonus->id);
    }

    public function testItIsNotAvailableIfAnotherBonusWasUsedOnSameDeposit()
    {
        SportsBonus::redeem($this->bonus->id);

        SportsBonus::cancel();

        $newBonus = $this->createBonus([
            'bonus_type_id' => 'first_deposit',
            'min_odd' => 1.2,
            'value_type' => 'percentage',
            'deadline' => 4,
            'rollover_coefficient' => 2,
            'value' => 100,
        ]);

        $newBonus->depositMethods()->create([
            'deposit_method_id' => 'bank_transfer'
        ]);

        $newBonus->targets()->create([
            'target_id' => 'Risk0'
        ]);


        $this->assertBonusNotAvailable($newBonus->id);
    }

    public function testItIsNotAvailableIfNoTargetsOrUsernamesAreSelected()
    {
        $this->bonus->targets()->delete();

        $this->assertBonusNotAvailable();
    }

    public function testItIsAvailableIfOnlyTheUsernameIsTargeted()
    {
        $this->bonus->targets()->delete();

        $this->bonus->usernameTargets()->create([
            'username' => $this->user->username
        ]);

        $this->assertBonusAvailable();
    }

    public function testItIsAvailableForMultipleUsersIfMultipleUsernamesAreTargeted()
    {
        $this->bonus->targets()->delete();

        $this->bonus->usernameTargets()->create([
            'username' => $this->user->username
        ]);

        $this->assertBonusAvailable();

        $newUser = $this->createUserWithEverything([
            App\UserTransaction::class => [
                'status_id' => 'processed',
                'debit' => '100',
                'origin' => 'bank_transfer'
            ],
            App\UserStatus::class => [
                'status_id' => 'approved',
                'identity_status_id' => 'confirmed',
                'email_status_id' => 'confirmed',
                'iban_status_id' => 'confirmed',
                'address_status_id' => 'confirmed',
            ]
        ]);

        $newUser->balance->addAvailableBalance(100);

        auth()->login($newUser);

        SportsBonus::swapUser($newUser);

        $this->assertBonusNotAvailable();

        $this->bonus->usernameTargets()->create([
            'username' => $newUser->username
        ]);

        $this->assertBonusAvailable();
    }

    public function testBonusAmountPreviewIsCorrect()
    {
        $this->assertBonusPreview(10);
    }

    public function testItIsNotAvailableIfCasinoBonusWasUsedOnSameDeposit()
    {
        $anotherBonus = $this->createBonus([
            'bonus_origin_id' => 'casino',
            'bonus_type_id' => 'casino_deposit',
            'min_odd' => 1.2,
            'value_type' => 'percentage',
            'deadline' => 4,
            'rollover_coefficient' => 2,
            'value' => 100,
        ]);

        $anotherBonus->depositMethods()->create([
            'deposit_method_id' => 'bank_transfer'
        ]);

        $anotherBonus->targets()->create([
            'target_id' => 'Risk0'
        ]);

        CasinoBonus::redeem($anotherBonus->id);

        $this->assertBonusNotAvailable($this->bonus->id);

        CasinoBonus::cancel();

        $this->assertBonusNotAvailable($this->bonus->id);
    }
}
