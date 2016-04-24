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

    private $bonus;
    private $user;


    private function createUser() {
        $user = factory(App\User::class, 1)->create();
        $user->createInitialBalance(0);
        return $user;
    }

    private function createBonus($targets = ['Risk0']) {
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
        $availableBonuses = UserBonus::getActiveBonuses($user->id);
        foreach ($availableBonuses as $availableBonus)
            if ($availableBonus->id===$bonus->id)
                $this->assertTrue(true);
    }

    public function testRedeemBonus() {
        $user = $this->createUser();
        $bonus = $this->createBonus();
        UserBonus::redeemBonus($user, $bonus->id);
        $this->assertTrue(!!UserBonus::findActiveBonusByOrigin($user, 'sport'));
    }

    public function testCancelBonus() {
        $user = $this->createUser();
        $bonus = $this->createBonus();
        UserBonus::redeemBonus($user, $bonus->id);
        UserBonus::cancelBonus($user, $bonus->id);
        $this->assertFalse(!!UserBonus::findActiveBonusbyOrigin($bonus, 'sport'));
    }


    public function testSameRedeemBonusMultipleTimes() {
        $user = $this->createUser();
        $bonus = $this->createBonus();
        UserBonus::redeemBonus($user, $bonus->id);
        UserBonus::redeemBonus($user, $bonus->id);
        $this->assertTrue(UserBonus::getActiveBonuses($user->id)
            ->where('bonus_id', $bonus->id)
            ->count() === 1
        );
    }

    public function testRedeemBonusAgainstOtherActiveBonusOfSameOrigin() {
        $user = $this->createUser();
        $bonus = $this->createBonus();
        $newBonus = $this->createBonus();

        UserBonus::redeemBonus($user, $bonus->id);
        UserBonus::redeemBonus($user, $newBonus->id);

        $this->assertTrue(UserBonus::getActiveBonuses($user->id)
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