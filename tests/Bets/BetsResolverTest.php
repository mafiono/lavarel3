<?php

use Carbon\Carbon;

class BetsResolverTest extends BaseBetsTest
{
    protected $deadline = 10;

    protected $deposit = 100;

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

        auth()->login($this->user->fresh());
    }

    public function testBetWon()
    {
        $odd = 1.5 ^ 5;
        // OK
        $bet = $this->placeBetForUser($this->user->id, 10, $odd, [
            \App\UserBetEvent::class => ['odd' => 1.5, 'status' => 'won'],
        ], 5);

        $this->assertBalanceOfUser($this->user, 90);

        $this->resolveBet($bet, 'won');

        $this->assertBalanceOfUser($this->user, 90 + $odd * 10);
    }

    public function testBetPartialWon()
    {
        $bet = $this->placeBetForUser($this->user->id, 10, 30, [], [
            ['odd' => 2, 'status' => 'won'],
            ['odd' => 3, 'status' => 'returned'],
            ['odd' => 5, 'status' => 'won'],
        ]);

        $this->assertBalanceOfUser($this->user, 90);

        $this->resolveBet($bet, 'won');

        $this->assertBalanceOfUser($this->user, 90 + 10 * 2 * 5);
    }

    public function testBetReturnedWithLoss()
    {
        $bet = $this->placeBetForUser($this->user->id, 10, 30, [], [
            ['odd' => 2, 'status' => 'won'],
            ['odd' => 3, 'status' => 'returned'],
            ['odd' => 5, 'status' => 'lost'],
        ]);

        $this->assertBalanceOfUser($this->user, 90);

        $this->resolveBet($bet, 'lost');

        $this->assertBalanceOfUser($this->user, 90);
    }
}
