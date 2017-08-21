<?php

class CasinoDepositTest extends BaseBonusTest
{
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
            'rollover_coefficient' => 5,
            'value' => 10,
            'max_bonus' => 100,
            'deposit_count' => 1
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

    public function testItIsAvailableForFirstDeposit()
    {
        $this->assertBonusAvailable();
    }

    public function testItIsNotAvailableIdItsForSecondDeposit()
    {
        $this->bonus->update(['deposit_count' => '2']);

        $this->assertBonusNotAvailable();
    }

    public function testItIsAvailableAfterSecondDeposit()
    {
        $this->bonus->update(['deposit_count' => '2']);

        factory(App\UserTransaction::class)->create([
            'user_id' => $this->user->id,
            'status_id' => 'processed',
            'debit' => '100',
            'origin' => 'bank_transfer'
        ]);


        $this->assertBonusNotAvailable();
    }

    protected function assertBonusAvailable($bonusId = null)
    {
        $bonusId = $bonusId ?: $this->bonus->id;

        $this->assertTrue(!CasinoBonus::getAvailable()->where('id', $bonusId)->isEmpty());
    }

}