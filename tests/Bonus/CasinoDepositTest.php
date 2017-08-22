<?php

use Carbon\Carbon;

class CasinoDepositTest extends BaseBonusTest
{
    protected $bonusFacade = 'CasinoBonus';

    protected $deadline = 10;

    protected $deposit = 100;

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
            'bonus_origin_id' => 'casino',
            'bonus_type_id' => 'casino_deposit',
            'min_odd' => 2.2,
            'value_type' => 'percentage',
            'deadline' => $this->deadline,
            'rollover_coefficient' => 30,
            'value' => 10,
            'max_bonus' => 100,
        ]);

        $this->bonus->depositMethods()->create([
            'deposit_method_id' => 'bank_transfer'
        ]);

        $this->bonus->targets()->create([
            'target_id' => 'Risk0'
        ]);

        $this->user->balance->addAvailableBalance(100);

        auth()->login($this->user->fresh());
    }

    public function testItIsAvailableAfterDeposit()
    {
        $this->assertBonusAvailable();
    }

    public function testItIsAvailableAfterAnotherDeposit()
    {
        factory(App\UserTransaction::class)->create([
            'user_id' => $this->user->id,
            'status_id' => 'processed',
            'debit' => '100',
            'origin' => 'bank_transfer'
        ]);

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
        CasinoBonus::redeem($this->bonus->id);

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
        CasinoBonus::redeem($this->bonus->id);

        $this->user->balance = $this->user->balance->fresh();

        $this->seeInDatabase('user_bonus', [
            'user_id' => $this->user->id,
            'bonus_id' => $this->bonus->id,
            'bonus_head_id' => $this->bonus->head_id,
            'active' => 1,
            'deposited' => 1,
            'bonus_value' => $this->deposit * 0.1,
            'rollover_amount' => 0,
            'deadline_date' => CasinoBonus::getActive()->created_at->addDays($this->deadline),
        ]);
    }

    public function testItIsActiveAfterRedeem()
    {
        CasinoBonus::redeem($this->bonus->id);

        $this->assertHasActiveBonus();
    }

    public function testRedeemFailsWithInvalidBonusId()
    {
        $this->setExpectedException(App\Bonus\Casino\CasinoBonusException::class);

        CasinoBonus::redeem('invalidId');

        $this->assertHasNoActiveBonus();
    }

    public function testRedeemFailsAfterItHasBeenRedeemed()
    {
        CasinoBonus::redeem($this->bonus->id);

        $this->setExpectedException(App\Bonus\Casino\CasinoBonusException::class);

        CasinoBonus::redeem($this->bonus->id);

        $this->assertHasActiveBonus();
    }

    public function testItCanBeCancelledAfterRedeemed()
    {
        CasinoBonus::redeem($this->bonus->id);

        CasinoBonus::cancel();

        $this->assertHasNoActiveBonus();
    }
}