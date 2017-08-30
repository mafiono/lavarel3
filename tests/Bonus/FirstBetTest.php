<?php

use Carbon\Carbon;

class FirstBetTest extends BaseBonusTest
{
    protected $firstBet;

    protected $deadline = 10;

    protected $depositAmount = 100;

    public function setUp()
    {
        parent::setUp();

        $this->user = $this->createUserWithEverything([
            App\UserTransaction::class => [
                'status_id' => 'processed',
                'origin' => 'cc',
                'debit' => $this->depositAmount,
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
            'bonus_type_id' => 'first_bet',
            'min_odd' => 3,
            'value_type' => 'percentage',
            'deadline' => $this->deadline,
            'rollover_coefficient' => 0,
            'max_bonus' => 50,
            'value' => 50,
        ]);

        $this->bonus->depositMethods()->create([
            'deposit_method_id' => 'cc'
        ]);

        $this->bonus->targets()->create([
            'target_id' => 'Risk0'
        ]);

        $this->deposit = $this->user->transactions->last();

        $this->user->balance->addAvailableBalance($this->depositAmount);

        auth()->login($this->user->fresh());

        $this->firstBet = $this->placeBetForUser($this->user->id, 30, 3.5, [], 3);

        $this->resultBetAsLost($this->firstBet);
    }

    public function testItIsAvailableWhenFirstBetIsLost()
    {
        $this->assertBonusAvailable();
    }

    public function testItIsntAvailableBeforeUserPlaceFirstBet()
    {
        $this->deleteBet($this->firstBet);

        $this->assertBonusNotAvailable();
    }

    public function testBonusIsUnavailableIfFirstBetIsWon()
    {
        $this->firstBet->update(['status' => 'won']);

        $this->assertBonusNotAvailable();
    }

    public function testItShouldNotBeAvailableIfFirstBetNotMultiple()
    {
        $this->firstBet->update(['type' => 'single']);

        $this->assertBonusNotAvailable();
    }

    public function testBonusIsAvailableIfFirstBetIsReturnedAndTheSecondIsLost()
    {
        $this->firstBet->update(['status' => 'returned']);

        $secondBet = $this->placeBetForUser($this->user->id, 30, 3.5, [], 3);

        $this->resultBetAsLost($secondBet);

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
            'bonus_value' => $this->firstBet->amount * $this->bonus->value/100,
            'rollover_amount' => 0,
            'deadline_date' => SportsBonus::getActive()->created_at->addDays($this->deadline),
        ]);
    }

    public function testBonusAwardedDoesntSurpassMaxBonus()
    {
        $this->firstBet->update(['amount' => 100]);

        SportsBonus::redeem($this->bonus->id);

        $this->assertBonusOfUser($this->user, $this->bonus->max_bonus);
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

    public function testItIsConsumedAfterCancel()
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

        $newBonus->depositMethods()->create([
            'deposit_method_id' => 'cc'
        ]);

        $newBonus->targets()->create([
            'target_id' => 'Risk0'
        ]);


        SportsBonus::redeem($newBonus->id);
    }

    public function testDepositInferiorToMinDepositAwardsNoBonus()
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

        $this->assertBonusOfUser($this->user, 15);
    }

    public function testItIsUnavailableIfFirstBetOddsIsInferiorToMinOdds()
    {
        $this->firstBet->update(['odd' => 2]);

        $this->assertBonusNotAvailable();
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

    public function testItCantAutoCancelsAfterDeadlineDateWhenUserHasUnresolvedBetsWithBonus()
    {
        SportsBonus::redeem($this->bonus->id);

        $this->placeBetForUser($this->user->id, 15, 3.5, [], 3);

        SportsBonus::userBonus()->update([
            'deadline_date' => Carbon::now()->subHour(2)
        ]);

        Artisan::call('cancel-bonuses');

        SportsBonus::swapUser($this->user);

        $this->assertBonusWasNotConsumed($this->bonus->id);
    }

    public function testIfUserMakesWithdrawalWithoutRedeemBonusThenBonusIsStillAvailable()
    {
        $this->assertBonusAvailable();

        $this->createWithdrawalFromUserAccount($this->user->id, 100);

        $this->assertBonusAvailable();
    }


    public function testIfUserMakesWithdrawalWithoutUnresolvedBetsThenBonusIsCancelled()
    {
        SportsBonus::redeem($this->bonus->id);

        $this->createWithdrawalFromUserAccount($this->user->id, 25);

        $this->assertBonusWasConsumed();
    }

    public function testItCreatesUserTransactionWhenRedeemed()
    {
        SportsBonus::redeem($this->bonus->id);

        $balance = $this->user->balance->fresh();

        $bonusAmount = $this->firstBet->amount * $this->bonus->value/100;

        $this->seeInDatabase('user_transactions', [
            'user_id' => $this->user->id,
            'origin' => 'sport_bonus',
            'debit_bonus' => number_format($bonusAmount, 2),
            'initial_balance' => number_format($balance->balance_available, 2),
            'final_balance' => number_format($balance->balance_available, 2),
            'initial_bonus' => number_format(0, 2),
            'final_bonus' => number_format($bonusAmount, 2),
            'description' => 'Resgate de bónus ' . $this->bonus->title,
        ]);
    }

    public function testItCreatesUserTransactionWhenEnds()
    {
        SportsBonus::redeem($this->bonus->id);

        $initialBonus = $this->user->balance->fresh()->balance_bonus;

        SportsBonus::cancel($this->bonus->id);

        $balance = $this->user->balance->fresh();

        $this->seeInDatabase('user_transactions', [
            'user_id' => $this->user->id,
            'origin' => 'sport_bonus',
            'credit_bonus' => number_format($initialBonus, 2),
            'initial_balance' => number_format($balance->balance_available, 2),
            'final_balance' => number_format($balance->balance_available, 2),
            'initial_bonus' => number_format($initialBonus, 2),
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

    public function testItIsAvailableEvenIfUserPlacedOtherBets()
    {
        $this->placeBetForUser($this->user->id, 4, 3.5);

        $this->assertBonusAvailable();
    }

    public function testItCanHaveMoreThatOneBonusAvailableForSameDeposit()
    {
        $newBonus = $this->createBonus([
            'bonus_type_id' => 'first_bet',
            'min_odd' => 3,
            'value_type' => 'percentage',
            'deadline' => 4,
            'rollover_coefficient' => 2,
            'value' => 50,
        ]);

        $newBonus->depositMethods()->create([
            'deposit_method_id' => 'cc'
        ]);

        $newBonus->targets()->create([
            'target_id' => 'Risk0'
        ]);

        $this->assertBonusAvailable($this->bonus->id);

        $this->assertBonusAvailable($newBonus->id);
    }

    public function testItIsNotAvailableIfAnotherBonusWasUsedOnTheSameDeposit()
    {
        $anotherBonus = $this->createBonus([
            'bonus_type_id' => 'first_deposit',
            'min_odd' => 3,
            'value_type' => 'percentage',
            'deadline' => $this->deadline,
            'rollover_coefficient' => 5,
            'max_bonus' => 50,
            'value' => 50,

        ]);

        $anotherBonus->depositMethods()->create([
            'deposit_method_id' => 'cc'
        ]);

        $anotherBonus->targets()->create([
            'target_id' => 'Risk0'
        ]);

        $this->assertBonusAvailable($anotherBonus->id);

        SportsBonus::redeem($anotherBonus->id);

        $this->assertBonusNotAvailable($this->bonus->id);

        $anotherBonus->update(['bonus_type_id' => 'first_bet']);

        $this->assertBonusNotAvailable($this->bonus->id);
    }

    public function testItIsNotAvailableAfterIfNextDepositHasNotBets()
    {
        factory('App\UserTransaction')->create([
            'user_id' => $this->user->id,
            'status_id' => 'processed',
            'origin' => 'cc',
            'debit' => '100',
            'created_at' => Carbon::now()->addSeconds(1)
        ]);

        $this->assertBonusNotAvailable();
    }

    public function testItIsAvailableAndRedeemsTheCorrectBonusIfThereIsALostBetAfterSecondDeposit()
    {
        factory('App\UserTransaction')->create([
            'user_id' => $this->user->id,
            'status_id' => 'processed',
            'origin' => 'cc',
            'debit' => '100',
            'created_at' => Carbon::now()->addSeconds(1)
        ]);

        $secondBet = $this->placeBetForUser($this->user->id, 20, 3.5, [], 3);

        $this->resultBetAsLost($secondBet);

        $secondBet->update(['created_at' => Carbon::now()->addSeconds(2)]);

        $this->assertBonusAvailable();

        $this->redeem();

        $this->assertBonusOfUser($this->user, $secondBet->amount * $this->bonus->value/100);
    }

    public function testItIsntAvailableIfTheBonusAmountToBeAwardedIsInferiorTo1()
    {
        $this->firstBet->update(['amount' => 1.5]);

        $this->assertBonusNotAvailable();
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
                'origin' => 'cc'
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

        $this->bonus->targets()->create([
            'target_id' => 'Risk0'
        ]);

        $bet = $this->placeBetForUser($newUser->id, 30, 3.5, [], 3);

        $this->resultBetAsLost($bet);

        $this->assertBonusAvailable();
    }

    public function testBonusAmountPreviewIsCorrect()
    {
        $this->assertBonusPreview(15);
    }

    public function testItIsNotAvailableIfSportsBonusWasUsedOnSameDeposit()
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
            'deposit_method_id' => 'cc'
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
