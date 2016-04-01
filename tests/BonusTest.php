<?php

use App\User;
use App\Bonus;
use App\UserBonus;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class BonusTest extends TestCase {
    use DatabaseTransactions;

    protected $bonus;
    protected $user;

    public function setUp() {
        parent::setUp();
        $this->user = factory(App\User::class, 1)->make();
        $this->bonus = factory(App\Bonus::class, 1)->make();
    }

    public function testAvailableBonuses() {
        $user = $this->user;
        $user->save();
        $this->assertTrue($user->availableBonuses()->count()>1);
    }

    public function testRedeemBonus() {
        $bonus = $this->bonus;
        $user = $this->user;
        $bonus->save();
        $user->save();
        $user->redeemBonus($bonus->id);
        $this->assertTrue(!!$user->activeBonuses()
            ->where('active', 1)
            ->where('bonus_origin_id', 'sport')
            ->first()
        );
    }

    public function testFindActiveBonusByOrigin() {
        $bonus = $this->bonus;
        $user = $this->user;
        $bonus->save();
        $user->save();
        $user->redeemBonus($bonus->id);
        $this->assertTrue(!!$user->findActiveBonusbyOrigin('sport'));
    }

    public function testCancelBonus() {
        $bonus = $this->bonus;
        $user = $this->user;
        $bonus->save();
        $user->save();

        $user->redeemBonus($bonus->id);

        $user->cancelBonus($bonus->id);
        $this->assertFalse(!!$user->findActiveBonusbyOrigin('sport'));
    }


    public function testSameRedeemBonusMultipleTimes() {
        $bonus = $this->bonus;
        $user = $this->user;
        $bonus->save();
        $user->save();
        $user->redeemBonus($bonus->id);
        $user->redeemBonus($bonus->id);

        $this->assertTrue($user->activeBonuses()
            ->where('bonus_origin_id', 'sport')
            ->count() === 1
        );
    }

    public function testRedeemBonusAgainstOtherActiveBonusOfSameOrigin() {
        $bonus = $this->bonus;
        $newBonus = $this->bonus = factory(App\Bonus::class)->make();
        $user = $this->user;
        $bonus->save();
        $newBonus->save();
        $user->save();
        $user->redeemBonus($bonus->id);
        $user->redeemBonus($newBonus->id);

        $this->assertTrue($user->activeBonuses()
        ->where('bonus_origin_id', 'sport')
        ->count() === 1
        );

    }
}