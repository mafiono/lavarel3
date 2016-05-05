<?php


use App\Bets\FakePlaceBet;
use App\Bets\BetslipBetValidator;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BetsTest extends TestCase {
    use DatabaseTransactions;

    public function testPlaceBet()
    {

        $user = factory(App\User::class)->create();
        $user->profile()->save(factory(App\UserProfile::class)->make());
        $user->balance()->save(factory(App\UserBalance::class)->make());
        Auth::login($user);

        $bet = new FakePlaceBet($user, ['apiType' => 'everymatrix',
            'status' => 'waiting_result'
        ]);

        // test validation
        $this->assertTrue((new BetslipBetValidator($bet))->validate());
        $userBet = $bet->placeBet();

        // check if status is correct
        $this->seeInDatabase('user_bet_statuses', ['user_bet_id' => $userBet->id,
            'status_id' => 'waiting_result'
        ]);

        //transaction reflects bet amount;
        $trans = $userBet->currentStatus->transaction;
        $this->assertTrue(($trans->amount_balance + $trans->amount_bonus) === $userBet->amount);

        //check transaction balance
        $deltaBalance = $trans->initial_balance - $trans->final_balance;
        $deltaBonus = $trans->initial_bonus - $trans->final_bonus;
        $this->assertTrue(($deltaBalance+$deltaBonus) === $userBet->amount);
    }

    public function testWonResult()
    {
        $user = factory(App\User::class)->create();
        $user->profile()->save(factory(App\UserProfile::class)->make());
        $user->balance()->save(factory(App\UserBalance::class)->make());
        Auth::login($user);

        $bet = new FakePlaceBet($user, ['apiType' => 'everymatrix',
            'status' => 'won'
        ]);

        // test if is valid
        $this->assertTrue((new BetslipBetValidator($bet))->validate());

        $userBet = $bet->setWonResult();
    }

    public function testLostResult()
    {

    }

    public function testCancelResult()
    {

    }

    private function makeBet($faker)
    {
        return new Bet([
            'apiType' => 'betportugal',
            'apiId' => '',
            'amount' => 69,
            'type' => $this->getType(),
            'odd' => $this->getOdd(),
            'status' => $this->getStatus(),
            'gameDate' => $this->getGameDate(),
            'userId' => $this->getUser()->id,

        ], User::find(48));
    }
}