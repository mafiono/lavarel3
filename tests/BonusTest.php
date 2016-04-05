<?php

use App\User;
use App\Bonus;
use App\UserBonus;
use App\BonusTargets;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class BonusTest extends TestCase {
    use DatabaseTransactions;

    protected $bonus;
    protected $user;


    protected function createUser() {
        $user = factory(App\User::class, 1)->create();
        $user->createInitialBalance('xxx');
        return $user;
    }

    protected function createBonus($targets = ['Risk0']) {
        $bonus = factory(App\Bonus::class, 1)->create();
        foreach ($targets as $target)
            BonusTargets::create([
                'bonus_id' => $bonus->id,
                'target_id' => $target
            ]);
        return $bonus;
    }


    public function testAvailableBonuses() {
        $user = $this->createUser();
        $bonus = $this->createBonus();
        $this->assertTrue($user->availableBonuses()->count()===1);
    }

    public function testRedeemBonus() {
        $user = $this->createUser();
        $bonus = $this->createBonus();
        $user->redeemBonus($bonus->id);
        $this->assertTrue(!!$user->activeBonuses()
            ->where('active', 1)
            ->where('bonus_origin_id', 'sport')
            ->first()
        );
    }

    public function testFindActiveBonusByOrigin() {
        $user = $this->createUser();
        $bonus = $this->createBonus();
        $user->redeemBonus($bonus->id);
        $this->assertTrue(!!$user->findActiveBonusbyOrigin('sport'));
    }

    public function testCancelBonus() {
        $user = $this->createUser();
        $bonus = $this->createBonus();
        $user->redeemBonus($bonus->id);
        $user->cancelBonus($bonus->id);
        $this->assertFalse(!!$user->findActiveBonusbyOrigin('sport'));
    }


    public function testSameRedeemBonusMultipleTimes() {
        $user = $this->createUser();
        $bonus = $this->createBonus();
        $user->redeemBonus($bonus->id);
        $user->redeemBonus($bonus->id);
        $this->assertTrue($user->activeBonuses()
            ->where('bonus_origin_id', 'sport')
            ->count() === 1
        );
    }

    public function testRedeemBonusAgainstOtherActiveBonusOfSameOrigin() {
        $user = $this->createUser();
        $bonus = $this->createBonus();
        $newBonus = $this->createBonus();

        $user->redeemBonus($bonus->id);
        $user->redeemBonus($newBonus->id);

        $this->assertTrue($user->activeBonuses()
        ->where('bonus_origin_id', 'sport')
        ->count() === 1
        );

    }

//    public static function makeHash($id, $seed=104729) {
//        $n = $id + $seed;
//        $hash = '';
//
//        while ($n) {
//            $c = $n % 26;
//            $n = floor($n / 26);
//            $hash .= chr(ord('A')+$c);
//        }
//
//        return str_pad($hash, 5, 'A', STR_PAD_LEFT);
//    }
}