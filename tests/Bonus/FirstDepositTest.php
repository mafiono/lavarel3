<?php

use App\UserBet;
use Carbon\Carbon;

class FirstDepositTest extends BaseBonusTest
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

        $this->bonus = $this->createBonus([
            'bonus_type_id' => 'first_deposit',
            'min_odd' => 1,
            'value_type' => 'percentage',
            'deadline' => 10,
            'rollover_coefficient' => 5,
            'value' => 50,
        ]);

        $this->user->balance->addAvailableBalance(100);

        auth()->login($this->user);
    }

    public function testIsAvailable()
    {
        $this->assertBonusAvailable();
    }

    public function testAvailabilityWhenNotBetweenAvailableInterval()
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

    public function testAvailabilityAfterRedeem()
    {
        SportsBonus::redeem($this->bonus->id);

        $this->assertBonusNotAvailable();
    }

    public function testAvailabilityWhenNotCurrent()
    {
        $this->bonus->current = false;
        $this->bonus->save();

        $this->assertBonusNotAvailable();
    }

    public function testAvailabilityWithoutUserTransactions()
    {
        $this->user->transactions()->delete();

        $this->assertBonusNotAvailable();
    }

    public function testRedeemCorrectness()
    {
        SportsBonus::redeem($this->bonus->id);

        $this->user->balance =  $this->user->balance->fresh();

        $this->seeInDatabase('user_bonus', [
            'user_id' => $this->user->id,
            'bonus_id' => $this->bonus->id,
            'bonus_head_id' => $this->bonus->head_id,
            'active' => 1,
            'deposited' => 1,
            'bonus_value' => 50,
            'rollover_amount' => 5 * (100 + 50),
        ]);

        $this->assertTrue(
            SportsBonus::getActive()->deadline_date->diffInSeconds(Carbon::now()->addDay($this->bonus->deadline)) < 60
        );
    }

    public function testIsActiveAfterRedeem()
    {
        SportsBonus::redeem($this->bonus->id);

        $this->assertHasActiveBonus();
    }


    public function testDepositAmountAfterRedeem()
    {
        SportsBonus::redeem($this->bonus->id);

        $this->assertTrue(
            $this->user->balance->balance_bonus*1 === (
                $this->user->transactions->first()->debit * $this->bonus->value * 0.01
            )
        );
    }

    public function testRedeemWithInvalidBonusId()
    {
        $this->setExpectedException(App\Bonus\SportsBonusException::class);

        SportsBonus::redeem('invalidId');

        $this->assertHasNoActiveBonus();
    }

    public function testRedeemWithUnavailableBonus()
    {
        $availableUntil = $this->bonus->available_until;
        $this->bonus->available_until = Carbon::now()->subDay(1);
        $this->bonus->save();

        $this->setExpectedException(App\Bonus\SportsBonusException::class);

        SportsBonus::redeem($this->bonus->id);

        $this->bonus->available_until = $availableUntil;
        $this->bonus->save();

        $this->assertHasNoActiveBonus();
    }

    public function testRedeemMultipleTimes()
    {
        SportsBonus::redeem($this->bonus->id);

        $this->setExpectedException(App\Bonus\SportsBonusException::class);

        SportsBonus::redeem($this->bonus->id);

        $this->assertHasActiveBonus();
    }

    public function testCancelCorrectness()
    {
        SportsBonus::redeem($this->bonus->id);

        SportsBonus::cancel();

        $this->assertHasNoActiveBonus();
    }

    public function testRedeemAfterCancel()
    {
        SportsBonus::redeem($this->bonus->id);

        SportsBonus::cancel();

        $this->setExpectedException(App\Bonus\SportsBonusException::class);

        SportsBonus::redeem($this->bonus->id);

        $this->assertHasNoActiveBonus();
    }

    public function testCancelMultipleTimes()
    {
        SportsBonus::redeem($this->bonus->id);

        SportsBonus::cancel();

        $this->setExpectedException(App\Bonus\SportsBonusException::class);

        SportsBonus::redeem($this->bonus->id);

        $this->assertHasNoActiveBonus();
    }

    public function testCancelAsSelfExcluded()
    {
        SportsBonus::redeem($this->bonus->id);

        $this->user->status->update([
            'selfexclusion_status_id' => 'minimum_period',
        ]);

        $this->setExpectedException(App\Bonus\SportsBonusException::class);

        SportsBonus::cancel();
    }

    public function testConsumedCorrectness()
    {
        SportsBonus::redeem($this->bonus->id);

        $this->assertBonusWasNotConsumed();

        SportsBonus::cancel();

        $this->assertBonusWasConsumed();
    }

    public function testBonusIsNotAvailableIfUserHasPlacedBets()
    {
        $this->assertBonusAvailable();

        $this->placeBetForUser($this->user->id, 2);

        $this->assertBonusNotAvailable();
    }

    public function testSecondRedeemOfAnotherBonusShouldFail()
    {
        SportsBonus::redeem($this->bonus->id);

        $this->setExpectedException(App\Bonus\SportsBonusException::class);

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
}
