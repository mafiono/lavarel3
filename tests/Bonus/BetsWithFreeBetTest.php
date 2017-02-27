<?php

use Carbon\Carbon;

class BetsWithFreeBetTest extends BaseBonusTest
{
    public function setUp()
    {
        parent::setUp();

        $this->user = $this->createUserWithEverything([
            App\UserBetTransaction::class => [
                'status_id' => 'processed',
                'debit' => '100',
            ],
            App\UserStatus::class => [
                'status_id' => 'approved',
                'identity_status_id' => 'confirmed',
                'email_status_id' => 'confirmed',
                'iban_status_id' => 'confirmed',
                'address_status_id' => 'confirmed',
            ]
        ]);

        $this->user->balance->addAvailableBalance(100);

        $this->bonus = $this->createBonus([
            'bonus_type_id' => 'free_bet',
            'min_odd' => 1,
            'value_type' => 'absolute',
            'deadline' => 10,
            'rollover_coefficient' => 0,
            'value' => 50,
        ]);

        auth()->login($this->user->fresh());

        SportsBonus::redeem($this->bonus->id);
    }

    public function testCancelWithUnresolvedBets()
    {
        $this->placeBetForUser($this->user->id, 2);

        $this->setExpectedException(App\Bonus\SportsBonusException::class);

        SportsBonus::cancel();
    }

    public function testBonusChargeFromBet()
    {
        $bet = $this->placeBetForUser($this->user->id, 2.5);

        $this->assertBetAmountCharge($bet, 0);

        $this->assertBetBonusCharge($bet, 2.5);
    }

    public function testBonusChargeFromMultiBet()
    {
        $bet = $this->placeBetForUser($this->user->id, 3, 1.5, [], 3);

        $this->assertBetAmountCharge($bet, 0);

        $this->assertBetBonusCharge($bet, 3);
    }

    public function testChargeFromBetAfterCancelBonus()
    {
        SportsBonus::cancel();

        $bet = $this->placeBetForUser($this->user->id, 2.33);

        $this->assertBetAmountCharge($bet, 2.33);

        $this->assertBetBonusCharge($bet, 0);
    }

    public function testBonusLostResultFromBet()
    {
        $bet = $this->placeBetForUser($this->user->id, 2.5);

        $this->resultBetAsLost($bet);

        $this->assertBetAmountDepositIsCorrect($bet, 0);

        $this->assertBetBonusDepositIsCorrect($bet, 0);
    }

    public function testThatReturnedResultFromBetIsTheAmountOfTheBonusAndNotTheAmountWagered()
    {
        $bet = $this->placeBetForUser($this->user->id, 2.5);

        $this->resultBetAsReturned($bet);

        $this->assertBetAmountDepositIsCorrect($bet, 0);

        $this->assertBetBonusDepositIsCorrect($bet, SportsBonus::userBonus()->bonus_value);
    }

    public function testFullBonusIsChargedIfNoBalanceAvailable()
    {
        $this->user->balance->subtractAvailableBalance(100);

        $bet = $this->placeBetForUser($this->user->id, 2.5);

        $this->assertBetBonusCharge($bet, 2.5);
    }

    public function testFullBalanceIsChargedIfNoBonusAvailableWhileBonusActive()
    {
        $this->user->balance->subtractBonus(50);

        $bet = $this->placeBetForUser($this->user->id, 2.5);

        $this->assertBetAmountCharge($bet, 2.5);

        $this->assertBetBonusCharge($bet, 0);
    }

    public function testBonusWageredCorrectness()
    {
        $this->placeBetForUser($this->user->id, 2.5);

        $this->seeInDatabase('user_bonus', [
            'user_id' => $this->user->id,
            'bonus_id' => $this->bonus->id,
            'bonus_wagered' => 2.5,
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
        $this->placeBetForUser($this->user->id, 2);

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

    public function testThatBonusCanOnlyBeUsedOnce()
    {
        $bet = $this->placeBetForUser($this->user->id, 2.5);

        $this->assertBetAmountCharge($bet, 0);

        $this->assertBetBonusCharge($bet, 2.5);

        $bet = $this->placeBetForUser($this->user->id, 2.5);

        $this->assertBetAmountCharge($bet, 2.5);

        $this->assertBetBonusCharge($bet, 0);
    }

    public function testBetNotApplicableIfBetAmountGreaterThanBonusAmount()
    {
        $bet = $this->placeBetForUser($this->user->id, 60);

        $this->assertBetAmountCharge($bet, 60);

        $this->assertBetBonusCharge($bet, 0);
    }

    public function testThatEvenIfTheBetAmountIsLesserTheBonusAmountThenBonusAmountDropsToZero()
    {
        $this->placeBetForUser($this->user->id, 40);

        $this->assertBonusOfUser($this->user, 0);
    }

    public function testIfBetWinThenTheCorrectBalanceIsIncreased()
    {
        $bet = $this->placeBetForUser($this->user->id, 50, 1.60);

        $this->resultBetAsWin($bet);

        $this->assertBalanceOfUser($this->user, 100 + round(50 * 0.60, 2));
    }
}
