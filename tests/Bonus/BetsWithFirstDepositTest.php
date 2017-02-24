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
            'min_odd' => 1,
            'value_type' => 'percentage',
            'deadline' => 10,
            'rollover_coefficient' => 5,
            'value' => 100,
        ]);

        auth()->login($this->user->fresh());

        SportsBonus::redeem($this->bonus->id);
    }

    public function testCancelWithUnresolvedBets()
    {
        $this->placeBetForUser($this->user->id, 2, 2, [], 2);

        $this->setExpectedException(App\Bonus\SportsBonusException::class);

        SportsBonus::cancel();
    }

    public function testBonusChargeFromBet()
    {
        $bet = $this->placeBetForUser($this->user->id, 2.5);

        $this->assertBetAmountCharge($bet, 2.5);
    }

    public function testBonusChargeFromMultiBet()
    {
        $bet = $this->placeBetForUser($this->user->id, 3, 1.5, [], 3);

        $this->assertBetAmountCharge($bet, 2.1);

        $this->assertBetBonusCharge($bet, 0.9);
    }


    public function testChargeFromBetAfterCancelBonus()
    {
        SportsBonus::cancel();

        $bet = $this->placeBetForUser($this->user->id, 2.33);

        $this->assertBetAmountCharge($bet, 2.33);

        $this->assertBetBonusCharge($bet, 0);
    }

    public function testBonusWinResultFromBet()
    {
        $bet = $this->placeBetForUser($this->user->id, 2.5);

        $this->resultBetAsWin($bet);

        $this->assertBetAmountDepositIsCorrect($bet);

        $this->assertBetBonusDepositIsCorrect($bet);
    }

    public function testBonusLostResultFromBet()
    {
        $bet = $this->placeBetForUser($this->user->id, 2.5);

        $this->resultBetAsLost($bet);

        $this->assertBetAmountDepositIsCorrect($bet, 0);

        $this->assertBetBonusDepositIsCorrect($bet, 0);
    }

    public function testBonusCancelResultFromBet()
    {
        $bet = $this->placeBetForUser($this->user->id, 2.5, 2, [], 2);

        $this->resultBetAsReturned($bet);

        $this->assertBetAmountDepositIsCorrect($bet, 1.75);

        $this->assertBetBonusDepositIsCorrect($bet, 0.75);
    }

    public function testFullBonusIsChargedIfNoBalanceAvailable()
    {
        $this->user->balance->subtractAvailableBalance(100);

        $bet = $this->placeBetForUser($this->user->id, 2.5, 1.5, [], 2);

        $this->assertBetBonusCharge($bet, 2.5);
    }

    public function testRemainingBonusIsChargedIfNoBalanceAvailable()
    {
        $this->user->balance->subtractAvailableBalance(99);

        $bet = $this->placeBetForUser($this->user->id, 2.5, 2, [], 2);

        $this->assertBetAmountCharge($bet, 1);

        $this->assertBetBonusCharge($bet, 1.5);
    }

    public function testFullBalanceIsChargedIfNoBonusAvailableWhileBonusActive()
    {
        $this->user->balance->subtractBonus(100);

        $bet = $this->placeBetForUser($this->user->id, 2.5, 2, [], 2);

        $this->assertBetAmountCharge($bet, 2.5);

        $this->assertBetBonusCharge($bet, 0);
    }

    public function testRemainingBalanceIsChargedIfNoBonusAvailableWhileBonusActive()
    {
        $this->user->balance->subtractBonus(99.60);

        $bet = $this->placeBetForUser($this->user->id, 2.5, 2.4, [], 2);

        $this->assertBetAmountCharge($bet, 2.1);

        $this->assertBetBonusCharge($bet, 0.4);
    }

    public function testBonusWageredCorrectness()
    {
        $this->placeBetForUser($this->user->id, 2.5, 2.4, [], 2);

        $this->seeInDatabase('user_bonus', [
            'user_id' => $this->user->id,
            'bonus_id' => $this->bonus->id,
            'bonus_wagered' => 0.75,
        ]);

        $this->placeBetForUser($this->user->id, 3, 2, [], 2);

        $this->seeInDatabase('user_bonus', [
            'user_id' => $this->user->id,
            'bonus_id' => $this->bonus->id,
            'bonus_wagered' => 0.75 + 0.9,
        ]);

        //Bonus not applicableTo bet
        $this->placeBetForUser($this->user->id, 2, 0.9, [], 2);

        $this->seeInDatabase('user_bonus', [
            'user_id' => $this->user->id,
            'bonus_id' => $this->bonus->id,
            'bonus_wagered' => 0.75 + 0.9,
        ]);
    }

    public function testBetWithEventStartDateBeyondDeadline()
    {
        $bet = $this->placeBetForUser($this->user->id, 2.5, 1.4, [
            App\UserBetEvent::class => ['game_date' => Carbon::now()->addMonths(50)]
        ]);

        $this->assertBetAmountCharge($bet, 2.5);

        $this->assertBetBonusCharge($bet, 0);
    }

    public function testBonusCompletion()
    {
        $this->resultBetAsWin($this->placeBetForUser($this->user->id, 100, 2, [], 2));

        $this->resultBetAsWin($this->placeBetForUser($this->user->id, 200, 2, [], 2));

        $this->resultBetAsWin($this->placeBetForUser($this->user->id, 300, 2, [], 2));

        $this->resultBetAsWin($this->placeBetForUser($this->user->id, 400, 2, [], 2));

        $this->resultBetAsWin($this->placeBetForUser($this->user->id, 500, 2, [], 2));

        $this->resultBetAsWin($this->placeBetForUser($this->user->id, 600, 2, [], 2));

        $this->resultBetAsWin($this->placeBetForUser($this->user->id, 1300, 2, [], 2));

        $this->seeInDatabase('user_transactions', [
            'user_id' => $this->user->id,
            'origin' => 'SportsBonus',
            'transaction_details' => 'FIRST_DEPOSIT bonus nº' . SportsBonus::userBonus()->id,
            'debit' => 100 + (100 + 200 + 300 + 400 + 500 + 600 + 1300) * 0.3,
        ]);

        $this->assertBalanceOfUser(
            $this->user,
            (
                100 + (100 + 200 + 300 + 400 + 500 + 600 + 1300) * 0.3 +
                100 + (100 + 200 + 300 + 400 + 500 + 600 + 1300) * 0.7
            )
        );

        $this->assertBonusOfUser($this->user, 0);

        $this->assertBonusWasConsumed($this->bonus->id);
    }

    public function testBonusAutoCancelByDecreasingBonusToZero()
    {
        $this->resultBetAsLost($this->placeBetForUser($this->user->id, 100, 2, [], 2));

        $this->resultBetAsLost($this->placeBetForUser($this->user->id, 100, 2, [], 2));

        $this->assertBalanceOfUser($this->user, 0);

        $this->assertBonusOfUser($this->user, 0);

        $this->assertBonusWasConsumed($this->bonus->id);
    }

    public function testBonusAutoCancelByPassingDeadlineDate()
    {
        SportsBonus::userBonus()->update([
            'deadline_date' => Carbon::now()->subHour(2)
        ]);

        Artisan::call('cancel-bonuses');

        SportsBonus::swapUser($this->user);

        $this->assertBonusWasConsumed($this->bonus->id);
    }

    public function testBonusIsNotChargedAfterDeadlineDate()
    {
        SportsBonus::userBonus()->update([
            'deadline_date' => Carbon::now()->subHour(2)
        ]);

        $bet = $this->placeBetForUser($this->user->id, 2);

        $this->assertBetBonusCharge($bet, 0);

        $this->assertBetAmountCharge($bet, 2);
    }

    public function testBonusAutoCancelByPassingDeadlineDateWithUnresolvedBets()
    {
        $this->placeBetForUser($this->user->id, 2, 2, [], 2);

        SportsBonus::userBonus()->update([
            'deadline_date' => Carbon::now()->subHour(2)
        ]);

        Artisan::call('cancel-bonuses');

        SportsBonus::swapUser($this->user);

        $this->assertBonusWasNotConsumed($this->bonus->id);
    }

    public function testThatBonusIsNotChargedIfBetOddsInferiorToMinimumOdd()
    {
        $this->bonus->update([
            'min_odd' => 2,
        ]);

        SportsBonus::swapUser($this->user);

        $bet = $this->placeBetForUser($this->user->id, 2, 1.5);

        $this->assertBetBonusCharge($bet, 0);

        $this->assertBetAmountCharge($bet, 2);
    }
}
