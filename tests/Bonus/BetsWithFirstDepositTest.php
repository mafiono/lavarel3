<?php

use Carbon\Carbon;

class BetsWithFirstDepositTest extends BaseBonusTest
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
            'bonus_type_id' => 'first_deposit',
            'min_odd' => 3,
            'value_type' => 'percentage',
            'deadline' => 10,
            'rollover_coefficient' => 5,
            'value' => 10,
        ]);

        auth()->login($this->user->fresh());

        SportsBonus::redeem($this->bonus->id);
    }

    public function testItChargesTheCorrectAmounts()
    {
        $bet = $this->placeBetForUser($this->user->id, 10, 3.5, [], 3);

        $this->assertBetAmountCharge($bet, 9);

        $this->assertBetBonusCharge($bet, 1);
    }

    public function testBetIsntPlacedIfNotExistsBalance()
    {
        $this->setUserBalance($this->user, 0);

        $bet = $this->makeBetForUser($this->user->id, 10, 3.5, [], 3);

        $this->assertBetIsNotChargeable($bet);

        $this->setExpectedException(\Exception::class);

        $this->placeBet($bet);
    }

    public function testIfExistsEventsBelowMinEventOddThanBonusIsntUsed()
    {
        $bet = $this->placeBetForUser($this->user->id, 10, 1.2, [], 3);

        $this->assertBetAmountCharge($bet, 10);

        $this->assertBetBonusCharge($bet, 0);
    }

    public function testItCantBeCancelledWhenExistsUnresolvedBets()
    {
        $this->placeBetForUser($this->user->id, 10, 3.5, [], 3);

        $this->setExpectedException(\App\Bonus\SportsBonusException::class);

        SportsBonus::cancel();
    }

    public function testThatBonusIsntChargedFromSimpleBet()
    {
        $bet = $this->placeBetForUser($this->user->id, 10);

        $this->assertBetAmountCharge($bet, 10);

        $this->assertBetBonusCharge($bet, 0);
    }

    public function testBonusIsntChargedAfterCancelBonus()
    {
        SportsBonus::cancel();

        $bet = $this->placeBetForUser($this->user->id, 10, 3.5, [], 3);

        $this->assertBetAmountCharge($bet, 10);

        $this->assertBetBonusCharge($bet, 0);
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

        $this->assertBetAmountAwardedIsCorrect($bet, 9);

        $this->assertBetBonusAwardedIsCorrect($bet, 1);
    }

    public function testThatBonusWageredIsLogged()
    {
        $bet = $this->placeBetForUser($this->user->id, 10, 3.5, [], 3);

        $this->resultBetAsLost($bet);

        $this->seeInDatabase('user_bonus', [
            'user_id' => $this->user->id,
            'bonus_id' => $this->bonus->id,
            'bonus_wagered' => 1,
        ]);

        $bet = $this->placeBetForUser($this->user->id, 3, 3.5, [], 3);

        $this->resultBetAsLost($bet);

        $this->seeInDatabase('user_bonus', [
            'user_id' => $this->user->id,
            'bonus_id' => $this->bonus->id,
            'bonus_wagered' => 1 + 3 * 0.1,
        ]);

        //Bet not applicable, then bonus should not be wagered.
        $this->placeBetForUser($this->user->id, 2);

        $this->seeInDatabase('user_bonus', [
            'user_id' => $this->user->id,
            'bonus_id' => $this->bonus->id,
            'bonus_wagered' => 1 + 3 * 0.1,
        ]);
    }

    public function testThatBetWithDateBeyondDeadlineDoesNotUseBonus()
    {
        $bet = $this->placeBetForUser($this->user->id, 2.5, 3.5, [
            App\UserBetEvent::class => ['game_date' => Carbon::now()->addMonths(50)]
        ], 3);

        $this->assertBetAmountCharge($bet, 2.5);

        $this->assertBetBonusCharge($bet, 0);
    }

    public function testItIsCancelledWhenBonusBalanceDropsToZeroAndThereIsntUnresolvedBets()
    {
        $bet = $this->placeBetForUser($this->user->id, 100, 3.5, [], 3);

        $this->resultBetAsLost($bet);

        $this->assertBonusOfUser($this->user, 0);

        $this->assertBonusWasConsumed($this->bonus->id);
    }

    public function testThatNoBonusIsChargedAfterDeadlineDate()
    {
        SportsBonus::userBonus()->update([
            'deadline_date' => Carbon::now()->subHour(2)
        ]);

        $bet = $this->placeBetForUser($this->user->id, 10, 3.5, [], 3);

        $this->assertBetAmountCharge($bet, 10);

        $this->assertBetBonusCharge($bet, 0);
    }

    public function testItDoesntAutoCancelsWhenPassingDeadlineDateWithUnresolvedBets()
    {
        $this->placeBetForUser($this->user->id, 10, 3.5, [], 3);

        SportsBonus::userBonus()->update([
            'deadline_date' => Carbon::now()->subHour(2)
        ]);

        Artisan::call('cancel-bonuses');

        SportsBonus::swapUser($this->user);

        $this->assertBonusWasNotConsumed($this->bonus->id);
    }

    public function testItDoesntAutoCancelsWhenUserHasMadeWithdrawalWithUnresolvedBets()
    {
        $this->placeBetForUser($this->user->id, 10, 3.5, [], 3);

        $this->createWithdrawalFromUserAccount($this->user->id, 25);

        Artisan::call('cancel-bonuses');

        SportsBonus::swapUser($this->user);

        $this->assertBonusWasNotConsumed($this->bonus->id);
    }

    public function testThatBonusIsntChargedIfBetOddsInferiorToMinimumOdd()
    {
        $bet = $this->placeBetForUser($this->user->id, 10, 2.5, [], 3);

        $this->assertBetAmountCharge($bet, 10);

        $this->assertBetBonusCharge($bet, 0);
    }

    public function testIfUserMakesWithdrawalWithUnresolvedBetsWithWageredBonusThenBonusIsntCancelled()
    {
        $this->placeBetForUser($this->user->id, 5, 3.5, [], 3);

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

        $bet = $this->placeBetForUser($this->user->id, 5, 3.5, [], 3);

        $this->assertBetAmountCharge($bet, 5);

        $this->assertBetBonusCharge($bet, 0);
    }

    public function testUserCanBeAwardedFromBetWithBonusWinsAfterMakingWithdrawn()
    {
        $bet = $this->placeBetForUser($this->user->id, 5, 3.5, [], 3);

        $this->createWithdrawalFromUserAccount($this->user->id, 25);

        $this->resultBetAsWin($bet);

        $this->assertBetAmountAwardedIsCorrect($bet, 5 * 3.5);
    }

    public function testItCantAutoCancelsAfterDeadlineDateWhenUserHasUnresolvedBetsWithBonus()
    {
        $this->placeBetForUser($this->user->id, 5, 3.5, [], 3);

        SportsBonus::userBonus()->update([
            'deadline_date' => Carbon::now()->subHour(2)
        ]);

        Artisan::call('cancel-bonuses');

        SportsBonus::swapUser($this->user);

        $this->assertBonusWasNotConsumed($this->bonus->id);
    }

    public function testIfThereIsntEnoughBonusToPlaceBetThenNoBonusIsCharged()
    {
        $this->setUserBalance($this->user, 150);

        $bet = $this->placeBetForUser($this->user->id, 150, 3.5, [], 3);

        $this->assertBetAmountCharge($bet, 150);

        $this->assertBetBonusCharge($bet, 0);
    }

    public function testBonusAndBalanceIsCorrectDuringGamePlay()
    {
        $bet = $this->placeBetForUser($this->user->id, 10, 3.5, [], 2);

        $this->resultBetAsLost($bet);

        $this->assertBalanceOfUser($this->user, 90);

        $this->assertBonusOfUser($this->user, 10);

        $bet = $this->placeBetForUser($this->user->id, 5, 2);

        $this->resultBetAsWin($bet);

        $this->assertBalanceOfUser($this->user, 95);

        $this->assertBonusOfUser($this->user, 10);

        $bet = $this->placeBetForUser($this->user->id, 20, 4, [], 4);

        $this->resultBetAsWin($bet);

        $this->assertBetAmountCharge($bet, 18);

        $this->assertBetBonusCharge($bet, 2);

        $this->assertBalanceOfUser($this->user, 157);

        $this->assertBonusOfUser($this->user, 8);

        $bet = $this->placeBetForUser($this->user->id, 157, 3.1, [], 3);

        $this->resultBetAsReturned($bet);

        $this->assertBalanceOfUser($this->user, 157);

        $this->assertBonusOfUser($this->user, 8);

        $bet = $this->placeBetForUser($this->user->id, 100, 7.1);

        $this->resultBetAsLost($bet);

        $this->assertBalanceOfUser($this->user, 57);

        $this->assertBonusOfUser($this->user, 8);

        $bet = $this->placeBetForUser($this->user->id, 50, 3.1, [], 3);

        $this->resultBetAsLost($bet);

        $this->assertBalanceOfUser($this->user, 12);

        $this->assertBonusOfUser($this->user, 3);

        $bet = $this->placeBetForUser($this->user->id, 10, 4, [], 3);

        $this->resultBetAsWin($bet);

        $this->assertBalanceOfUser($this->user, 43);

        $this->assertBonusOfUser($this->user, 2);

        $bet = $this->placeBetForUser($this->user->id, 15, 4, [], 3);

        $this->resultBetAsLost($bet);

        $this->assertBalanceOfUser($this->user, 29.5);

        $this->assertBonusOfUser($this->user, 0.5);

        $bet = $this->placeBetForUser($this->user->id, 4, 4, [], 3);

        $this->resultBetAsWin($bet);

        $this->assertBalanceOfUser($this->user, 41.9);

        $this->assertBonusOfUser($this->user, 0);

        $this->assertBonusWasConsumed();
    }
}
