<?php

use Carbon\Carbon;

class BetsWithFirstDepositBetTest extends BaseBonusTest
{
    public function setUp()
    {
        parent::setUp();

        $this->user = $this->createUserWithEverything([
            App\UserBetTransaction::class => [
                'status_id' => 'processed',
                'debit' => '100',
            ],
            App\UserBalance::class => [
                'balance_available' => 0,
            ],
            App\UserStatus::class => [
                'status_id' => 'approved',
                'identity_status_id' => 'confirmed',
                'email_status_id' => 'confirmed',
                'iban_status_id' => 'confirmed',
                'address_status_id' => 'confirmed',
            ],
        ]);

        $this->user->balance->addAvailableBalance(100);

        $this->bonus = $this->createBonus([
            'bonus_type_id' => 'first_deposit_bet',
            'min_odd' => 3,
            'value_type' => 'percentage',
            'deadline' => 10,
            'rollover_coefficient' => 5,
            'value' => 100,
        ]);

        auth()->login($this->user->fresh());

        $firstBet = $this->placeBetForUser($this->user->id, 30, 3.5, [], 3);

        $this->resultBetAsLost($firstBet);

        SportsBonus::redeem($this->bonus->id);

        $this->setUserBalance($this->user, 0);
    }

    public function testItDoesNotChargeBonusIfExistsEnoughBalance()
    {
        $this->setUserBalance($this->user, 50);

        $bet = $this->placeBetForUser($this->user->id, 10, 3.5, [], 3);

        $this->assertBetAmountCharge($bet, 10);

        $this->assertBetBonusCharge($bet, 0);
    }

    public function testItChargesBonusIfNotExistsBalance()
    {
        $bet = $this->placeBetForUser($this->user->id, 10, 3.5, [], 3);

        $this->assertBetAmountCharge($bet, 0);

        $this->assertBetBonusCharge($bet, 10);
    }

    public function testItChargesTheRemainingInBonusIfBalanceIsInferiorToMinBetAmount()
    {
        $this->setUserBalance($this->user, 1);

        $bet = $this->placeBetForUser($this->user->id, 10, 3.5, [], 3);

        $this->assertBetAmountCharge($bet, 1);

        $this->assertBetBonusCharge($bet, 9);
    }

    public function testItDoestChargesTheRemainingInBonusIfBalanceIsntInferiorToMinBetAmount()
    {
        $this->setUserBalance($this->user, 2);

        $bet = $this->makeBetForUser($this->user->id, 10, 3.5, [], 3);

        $this->assertBetIsNotChargeable($bet);

        $this->setExpectedException(\Exception::class);

        $this->placeBet($bet);
    }

    public function testItCantBeCancelledWhenExistsUnresolvedBets()
    {
        $this->placeBetForUser($this->user->id, 10, 3.5, [], 3);

        $this->setExpectedException(\App\Bonus\SportsBonusException::class);

        SportsBonus::cancel();
    }

    public function testItFailsWhenChargeBonusFromSimpleBet()
    {
        $bet = $this->makeBetForUser($this->user->id, 10);

        $this->assertBetIsNotChargeable($bet);

        $this->setExpectedException(\Exception::class);

        $this->placeBet($bet);
    }

    public function testBonusChargeFailsAfterCancelBonus()
    {
        SportsBonus::cancel();

        $bet = $this->makeBetForUser($this->user->id, 10, 3.5, [], 3);

        $this->assertBetIsNotChargeable($bet);

        $this->setExpectedException(\Exception::class);

        $this->placeBet($bet);
    }

    public function testItAwardsTheCorrectBalanceFromBonusWhenWinFromBet()
    {
        $bet = $this->placeBetForUser($this->user->id, 10, 3.5, [], 3);

        $this->resultBetAsWin($bet);

        $this->assertBetAmountAwardedIsCorrect($bet, 10 * 3.5);

        $this->assertBetBonusAwardedIsCorrect($bet, 0);
    }

    public function testItAwardsNothingIfLostResultFromBet()
    {
        $bet = $this->placeBetForUser($this->user->id, 10, 3.5, [], 3);

        $this->resultBetAsLost($bet);

        $this->assertBetAmountAwardedIsCorrect($bet, 0);

        $this->assertBetBonusAwardedIsCorrect($bet, 0);
    }

    public function testItReturnsBonusIfBetIsReturned()
    {
        $bet = $this->placeBetForUser($this->user->id, 10, 3.5, [], 4);

        $this->resultBetAsReturned($bet);

        $this->assertBetAmountAwardedIsCorrect($bet, 0);

        $this->assertBetBonusAwardedIsCorrect($bet, 10);
    }

    public function testItReturnsTheCorrectBalanceAndBonusIfBetIsReturnedAndBalanceWithBonusWereWagered()
    {
        $this->setUserBalance($this->user, 1.5);

        $bet = $this->placeBetForUser($this->user->id, 16.5, 3.5, [], 4);

        $this->resultBetAsReturned($bet);

        $this->assertBetAmountAwardedIsCorrect($bet, 1.5);

        $this->assertBetBonusAwardedIsCorrect($bet, 15);
    }

    public function testThatBonusWageredIsLogged()
    {
        $bet = $this->placeBetForUser($this->user->id, 10, 3.5, [], 2);

        $this->resultBetAsLost($bet);

        $this->seeInDatabase('user_bonus', [
            'user_id' => $this->user->id,
            'bonus_id' => $this->bonus->id,
            'bonus_wagered' => 10,
        ]);

        $this->setUserBalance($this->user, 1);

        $bet = $this->placeBetForUser($this->user->id, 3, 3.5, [], 2);

        $this->resultBetAsLost($bet);

        $this->seeInDatabase('user_bonus', [
            'user_id' => $this->user->id,
            'bonus_id' => $this->bonus->id,
            'bonus_wagered' => 12,
        ]);

        $this->setUserBalance($this->user, 10);

        //Bet not applicable, then bonus should not be wagered.
        $this->placeBetForUser($this->user->id, 2);

        $this->seeInDatabase('user_bonus', [
            'user_id' => $this->user->id,
            'bonus_id' => $this->bonus->id,
            'bonus_wagered' => 12,
        ]);
    }

    public function testThatBetWithDateBeyondDeadlineDoesNotUseBonus()
    {
        $bet = $this->makeBetForUser($this->user->id, 2.5, 3.5, [
            App\UserBetEvent::class => ['game_date' => Carbon::now()->addMonths(50)]
        ], 3);

        $this->assertBetIsNotChargeable($bet);

        $this->setExpectedException(\Exception::class);

        $this->placeBet($bet);
    }

    public function testItIsCancelledWhenBonusBalanceDropsToZeroAndThereIsntUnresolvedBets()
    {
        $bet = $this->placeBetForUser($this->user->id, 15, 3.5, [], 2);

        $this->resultBetAsLost($bet);

        $this->assertBonusOfUser($this->user, 0);

        $this->assertBonusWasConsumed($this->bonus->id);
    }

    public function testThatNoBonusIsChargedAfterDeadlineDate()
    {
        SportsBonus::userBonus()->update([
            'deadline_date' => Carbon::now()->subHour(2)
        ]);

        $bet = $this->makeBetForUser($this->user->id, 10, 3.5, [], 2);

        $this->assertBetIsNotChargeable($bet);

        $this->setExpectedException(\Exception::class);

        $this->placeBet($bet);
    }

    public function testItDoesntAutoCancelsWhenPassingDeadlineDateWithUnresolvedBets()
    {
        $this->placeBetForUser($this->user->id, 10, 3.5, [], 2);

        SportsBonus::userBonus()->update([
            'deadline_date' => Carbon::now()->subHour(2)
        ]);

        Artisan::call('cancel-bonuses');

        SportsBonus::swapUser($this->user);

        $this->assertBonusWasNotConsumed($this->bonus->id);
    }

    public function testItDoesntAutoCancelsWhenUserHasMadeWithdrawalWithUnresolvedBets()
    {
        $this->placeBetForUser($this->user->id, 10, 3.5, [], 2);

        $this->createWithdrawalFromUserAccount($this->user->id, 25);

        Artisan::call('cancel-bonuses');

        SportsBonus::swapUser($this->user);

        $this->assertBonusWasNotConsumed($this->bonus->id);
    }

    public function testThatBonusIsntChargedIfBetOddsInferiorToMinimumOdd()
    {
        $bet = $this->makeBetForUser($this->user->id, 10, 2.5, [], 2);

        $this->assertBetIsNotChargeable($bet);

        $this->setExpectedException(\Exception::class);

        $this->placeBet($bet);
    }

    public function testItDoesntChargeBonusIfExistUnresolvedBetsWithoutBonus()
    {
        $this->setUserBalance($this->user, 10);

        $this->placeBetForUser($this->user->id, 10, 3.5, [], 2);

        $bet = $this->makeBetForUser($this->user->id, 5, 3.5, [], 2);

        $this->assertBetIsNotChargeable($bet);

        $this->setExpectedException(\Exception::class);

        $this->placeBet($bet);
    }

    public function testItDoesntChargeBonusIfExistUnresolvedBetsWithBonus()
    {
        $this->placeBetForUser($this->user->id, 10, 3.5, [], 2);

        $bet = $this->makeBetForUser($this->user->id, 5, 3.5, [], 2);

        $this->assertBetIsNotChargeable($bet);

        $this->setExpectedException(\Exception::class);

        $this->placeBet($bet);
    }

    public function testItCanChargeBonusAfterBetGetsResolved()
    {
        $bet1 = $this->placeBetForUser($this->user->id, 10, 3.5, [], 2);

        $bet2 = $this->makeBetForUser($this->user->id, 5, 3.5, [], 2);

        $this->assertBetIsNotChargeable($bet2);

        $this->resultBetAsLost($bet1);

        $this->assertBetIsChargeable($bet2);
    }

    public function testIfUserMakesWithdrawalWithUnresolvedBetsWithWageredBonusThenBonusIsntCancelled()
    {
        $this->setUserBalance($this->user, 0);

        $this->placeBetForUser($this->user->id, 5, 3.5, [], 2);

        $this->createWithdrawalFromUserAccount($this->user->id, 25);

        $this->assertBonusWasNotConsumed();
    }

    public function testIfUserMakesWithdrawalWithUnresolvedBetsWithoutWageredBonusThenBonusIsCancelled()
    {
        $this->setUserBalance($this->user, 10);

        $this->placeBetForUser($this->user->id, 10);

        $this->createWithdrawalFromUserAccount($this->user->id, 25);

        $this->assertBonusWasConsumed();
    }

    public function testUserCantPlaceBetsWithBonusAfterWithdrawnFromAccount()
    {
        $this->createWithdrawalFromUserAccount($this->user->id, 5);

        $bet = $this->makeBetForUser($this->user->id, 10, 3.5, [], 2);

        $this->assertBetIsNotChargeable($bet);
    }

    public function testUserCanBeAwardedFromBetWithBonusWinsAfterMakingWithdrawn()
    {
        $bet = $this->placeBetForUser($this->user->id, 5, 3.5, [], 2);

        $this->createWithdrawalFromUserAccount($this->user->id, 25);

        $this->resultBetAsWin($bet);

        $this->assertBetAmountAwardedIsCorrect($bet, 5 * 3.5);
    }
}
