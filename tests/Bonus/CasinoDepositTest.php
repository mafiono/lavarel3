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
            'bonus_type_id' => 'casino_deposit',
            'min_odd' => 2.2,
            'value_type' => 'percentage',
            'deadline' => $this->deadline,
            'rollover_coefficient' => 5,
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

    public function testItIsAvailableWithOneDeposit()
    {
        $this->assertBonusAvailable();
    }

    protected function assertBonusAvailable($bonusId = null)
    {
        $bonusId = $bonusId ?: $this->bonus->id;

        dd(CasinoBonus::getAvailable());

        $this->assertTrue(!CasinoBonus::getAvailable()->where('id', $bonusId)->isEmpty());
    }

}