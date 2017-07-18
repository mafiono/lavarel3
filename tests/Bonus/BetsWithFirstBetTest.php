<?php

use Carbon\Carbon;

class BetsWithFirstBetTest extends BaseBonusTest
{
    protected $firstBet;

    protected $deadline = 5;

    protected $deposit = 50;

    public function setUp()
    {
        parent::setUp();

        $this->user = $this->createUserWithEverything([
            App\UserTransaction::class => [
                'status_id' => 'processed',
                'origin' => 'mb',
                'debit' => $this->deposit,
            ],
            App\UserStatus::class => [
                'status_id' => 'approved',
                'identity_status_id' => 'confirmed',
                'email_status_id' => 'confirmed',
                'iban_status_id' => 'confirmed',
                'address_status_id' => 'confirmed',
            ],
        ]);

        $this->user->balance->addAvailableBalance($this->deposit);

        $this->bonus = $this->createBonus([
            'bonus_type_id' => 'first_bet',
            'min_odd' => 3,
            'value_type' => 'percentage',
            'deadline' => 10,
            'rollover_coefficient' => 5,
            'max_bonus' => 25,
            'value' => 100,
        ]);

        $this->bonus->depositMethods()->create([
            'deposit_method_id' => 'mb'
        ]);

        auth()->login($this->user->fresh());

        $firstBet = $this->placeBetForUser($this->user->id, 30, 3.5, [], 3);

        $this->resultBetAsLost($firstBet);

        SportsBonus::redeem($this->bonus->id);
    }

    public function testThatBetIsAcceptedIfItsAllIn()
    {
        $this->placeBetForUser($this->user->id, 25, 5, [], 3);

        $this->assertBonusOfUser($this->user, 0);
    }

    public function testThatBetIsNotAcceptedIfIfNotAllIn()
    {
        $bet = $this->makeBetForUser($this->user->id, 10, 5, [], 3);

        $validator = new BetValidator($bet);

        $this->setExpectedException(App\Bets\Bets\BetException::class);

        $validator->checkAllIn();
    }

    public function testThatBetIsNotAcceptedIfItsSimple()
    {
        $bet = $this->makeBetForUser($this->user->id, 15, 5);

        $validator = new BetValidator($bet);

        $this->setExpectedException(App\Bets\Bets\BetException::class);

        $validator->checkAllIn();
    }

    public function testThatBetIsNotAcceptedIfBetOddsInferiorToMinimumOdd()
    {
        $bet = $this->makeBetForUser($this->user->id, 25, 2.5, [], 4);

        $validator = new BetValidator($bet);

        $this->setExpectedException(App\Bets\Bets\BetException::class);

        $validator->checkAllIn();
    }

    public function testItAcceptsAllKindOfBetsAfterAllIn()
    {
        $this->placeBetForUser($this->user->id, 25, 5, [], 3);

        $this->assertBonusOfUser($this->user, 0);

        $this->placeBetForUser($this->user->id, 5, 2);

        $this->assertBalanceOfUser($this->user, 15);

        $this->placeBetForUser($this->user->id, 2.5, 8, [], 4);

        $this->assertBalanceOfUser($this->user, 12.5);
    }

    public function testAllInIsAcceptedIfDoesNotExistsBalance()
    {
        $this->setUserBalance($this->user, 0);

        $this->placeBetForUser($this->user->id, 25, 3.5, [], 3);

        $this->assertBonusOfUser($this->user, 0);
    }

    public function testItCantBeCancelledWhenExistsUnresolvedBets()
    {
        $this->placeBetForUser($this->user->id, 25, 3.5, [], 3);

        $this->setExpectedException(\App\Bonus\SportsBonusException::class);

        SportsBonus::cancel();
    }

    public function testItAwardsTheCorrectBalanceFromBonusWhenWinFromBet()
    {
        $bet = $this->placeBetForUser($this->user->id, 25, 3.5, [], 3);

        $this->resultBetAsWin($bet);

        $this->assertBetAmountAwardedIsCorrect($bet, 25 * 3.5);

        $this->assertBetBonusAwardedIsCorrect($bet, 0);
    }

    public function testItAwardsNothingIfLostResultFromBet()
    {
        $bet = $this->placeBetForUser($this->user->id, 25, 3.5, [], 3);

        $this->resultBetAsLost($bet);

        $this->assertBetAmountAwardedIsCorrect($bet, 0);

        $this->assertBetBonusAwardedIsCorrect($bet, 0);
    }

    public function testItReturnsBonusIfBetIsReturned()
    {
        $bet = $this->placeBetForUser($this->user->id, 25, 3.5, [], 4);

        $this->resultBetAsReturned($bet);

        $this->assertBetAmountAwardedIsCorrect($bet, 0);

        $this->assertBetBonusAwardedIsCorrect($bet, 25);
    }

    public function testThatBonusWageredIsLogged()
    {
        $bet = $this->placeBetForUser($this->user->id, 25, 3.5, [], 3);

        $this->seeInDatabase('user_bonus', [
            'user_id' => $this->user->id,
            'bonus_id' => $this->bonus->id,
            'bonus_wagered' => 25,
        ]);

        $this->resultBetAsReturned($bet);

        $this->placeBetForUser($this->user->id, 25, 3.5, [], 3);

        $this->seeInDatabase('user_bonus', [
            'user_id' => $this->user->id,
            'bonus_id' => $this->bonus->id,
            'bonus_wagered' => 50,
        ]);
    }

    public function testThatBetWithGameDateBeyondDeadlineIsNotAccepted()
    {
        $bet = $this->makeBetForUser($this->user->id, 25, 3.5, [
            App\UserBetEvent::class => ['game_date' => Carbon::now()->addMonths(50)]
        ], 3);

        $validator = new BetValidator($bet);

        $this->setExpectedException(App\Bets\Bets\BetException::class);

        $validator->checkAllIn();
    }

    public function testItIsAutoCancelledWhenBetResultsAsLost()
    {
        $bet = $this->placeBetForUser($this->user->id, 25, 3.5, [], 3);

        $this->resultBetAsLost($bet);

        $this->assertBonusOfUser($this->user, 0);

        $this->assertBonusWasConsumed($this->bonus->id);
    }

    public function testItIsAutoCancelledWhenBetResultsAsWin()
    {
        $bet = $this->placeBetForUser($this->user->id, 25, 3.5, [], 3);

        $this->resultBetAsWin($bet);

        $this->assertBonusOfUser($this->user, 0);

        $this->assertBonusWasConsumed($this->bonus->id);
    }

    public function testItIsntCancelledWhenBetResultsAsReturned()
    {
        $bet = $this->placeBetForUser($this->user->id, 25, 3.5, [], 3);

        $this->resultBetAsReturned($bet);

        $this->assertBonusOfUser($this->user, 25);

        $this->assertBonusWasNotConsumed($this->bonus->id);
    }

    public function testThatNoBonusIsChargedAfterDeadlineDate()
    {
        SportsBonus::userBonus()->update([
            'deadline_date' => Carbon::now()->subHour(2)
        ]);

        $bet = $this->makeBetForUser($this->user->id, 25, 3.5, [], 3);

        $validator = new BetValidator($bet);

        $this->setExpectedException(App\Bets\Bets\BetException::class);

        $validator->checkAllIn();
    }

    public function testItDoesntAutoCancelsWhenPassingDeadlineDateWithUnresolvedBets()
    {
        $this->placeBetForUser($this->user->id, 25, 3.5, [], 4);

        SportsBonus::userBonus()->update([
            'deadline_date' => Carbon::now()->subHour(2)
        ]);

        Artisan::call('cancel-bonuses');

        SportsBonus::swapUser($this->user);

        $this->assertBonusWasNotConsumed($this->bonus->id);
    }

    public function testIfUserMakesWithdrawalThenBonusIsCancelled()
    {
        $this->createWithdrawalFromUserAccount($this->user->id, 25);

        $this->assertBonusWasConsumed();
    }

    public function testItDoesntAutoCancelsWhenUserHasMadeWithdrawalWithUnresolvedBets()
    {
        $this->placeBetForUser($this->user->id, 25, 3.5, [], 3);

        $this->createWithdrawalFromUserAccount($this->user->id, 25);

        Artisan::call('cancel-bonuses');

        SportsBonus::swapUser($this->user);

        $this->assertBonusWasNotConsumed($this->bonus->id);
    }

    public function testIfUserWithUnresolvedBetsWithoutBonusWhenMakesWithdrawalThenBonusIsCancelled()
    {
        $this->placeBetForUser($this->user->id, 20, 1);

        $this->createWithdrawalFromUserAccount($this->user->id, 25);

        Artisan::call('cancel-bonuses');

        SportsBonus::swapUser($this->user);

        $this->assertBonusWasConsumed($this->bonus->id);
    }

    public function testThatUserCanPlaceSecondAllInAfterWithdrawnIfTheFirstAllInIsReturned()
    {
        $firstBet = $this->placeBetForUser($this->user->id, 25, 3.5, [], 3);

        $this->createWithdrawalFromUserAccount($this->user->id, 5);

        $this->resultBetAsReturned($firstBet);

        $secondBet = $this->placeBetForUser($this->user->id, 25, 3.5, [], 3);

        $this->assertBonusOfUser($this->user, 0);

        $this->resultBetAsWin($secondBet);

        $this->assertBonusOfUser($this->user, 0);

        $this->assertBalanceOfUser($this->user, 20 + 25 * 3.5);
    }

    public function testUserCanBeAwardedFromBetWithBonusWinsAfterMakingWithdrawn()
    {
        $bet = $this->placeBetForUser($this->user->id, 25, 3.5, [], 3);

        $this->createWithdrawalFromUserAccount($this->user->id, 25);

        $this->resultBetAsWin($bet);

        $this->assertBetAmountAwardedIsCorrect($bet, 25 * 3.5);
    }
}
