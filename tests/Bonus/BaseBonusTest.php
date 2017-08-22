<?php

use App\Bets\Bets\Bet;
use App\Bets\Bets\BetException;
use App\Bets\Bookie\BetBookie;
use App\Bets\Cashier\ChargeCalculator;
use App\Bonus;
use App\GlobalSettings;
use App\User;
use App\UserSession;
use App\UserTransaction;
use Illuminate\Database\Eloquent\Model;

abstract class BaseBonusTest extends TestCase
{
    protected $user;

    protected $bonus;

    protected $bonusFacade = 'SportsBonus';

    public function setUp()
    {
        parent::setUp();

        $this->app->make('db')->beginTransaction();

        $this->beforeApplicationDestroyed(function () {
            $this->app->make('db')->rollBack();
        });

        Model::unguard();
    }

    protected function createBonus($modifiers = [])
    {
        $bonus = factory(App\Bonus::class)->create($modifiers);

        $bonus->head_id = $bonus->id;

        $bonus->save();

        return $bonus;
    }

    protected function createUserWithEverything($modifiers = [])
    {
        $user = factory(App\User::class)->create($this->applyModifiers(
            [],
            App\User::class,
            $modifiers
        ));

        $session = factory(App\UserSession::class)->create($this->applyModifiers(
            ['user_id' => $user->id],
            App\UserSession::class,
            $modifiers
        ));

        factory(App\UserTransaction::class)->create($this->applyModifiers(
            ['user_id' => $user->id],
            App\UserTransaction::class,
            $modifiers
        ));

        factory(App\UserStatus::class)->create($this->applyModifiers(
            ['user_id' => $user->id],
            App\UserStatus::class,
            $modifiers
        ));

        factory(App\UserProfile::class)->create($this->applyModifiers(
            ['user_id' => $user->id, 'user_session_id' => $session->id],
            App\UserProfile::class,
            $modifiers
        ));

        factory(App\UserBalance::class)->create($this->applyModifiers(
            ['user_id' => $user->id, 'user_session_id' => $session->id],
            App\UserBalance::class,
            $modifiers
        ));

        return $user;
    }

    protected function applyModifiers($input, $model, $modifiers = [])
    {
        return array_key_exists($model, $modifiers)
            ? array_merge($input, $modifiers[$model])
            : $input;
    }

    protected function assertBonusAvailable($bonusId = null)
    {
        $bonusId = $bonusId ?: $this->bonus->id;

        $this->assertTrue(!$this->bonusFacade::getAvailable()->where('id', $bonusId)->isEmpty());
    }

    protected function assertBonusNotAvailable($bonusId = null)
    {
        $bonusId = $bonusId ?: $this->bonus->id;

        $this->assertTrue($this->bonusFacade::getAvailable()->where('id', $bonusId)->isEmpty());
    }


    protected function assertHasActiveBonus($bonusId = null)
    {
        $bonusId = $bonusId ?: $this->bonus->id;

        $this->assertTrue($this->bonusFacade::hasActive());

        $this->assertTrue($this->bonusFacade::getActive()->bonus_id === $bonusId);
    }

    protected function assertHasNoActiveBonus()
    {
        $this->assertTrue(!$this->bonusFacade::hasActive());

        $this->assertTrue(is_null($this->bonusFacade::getActive()));
    }

    protected function assertBonusWasConsumed($bonusId = null)
    {
        $bonusId = $bonusId ?: $this->bonus->id;

        $this->assertTrue(!$this->bonusFacade::getConsumed()->where('bonus_id', $bonusId)->isEmpty());
    }

    protected function assertBonusWasNotConsumed($bonusId = null)
    {
        $bonusId = $bonusId ?: $this->bonus->id;

        $this->assertTrue($this->bonusFacade::getConsumed()->where('bonus_id', $bonusId)->isEmpty());
    }

    protected function makeBetForUser($userId, $amount, $odds = 1.5, $modifiers = [], $eventsCount = 1)
    {
        $bet = factory(App\Bets\Bets\BetslipBet::class)->make($this->applyModifiers([
            'user_id' => $userId,
            'user_betslip_id' => factory(App\UserBetslip::class)->create([
                'user_id' => $userId
            ])->id,
            'api_bet_type' => '',
            'api_transaction_id' => '',
            'amount' => $amount,
            'odd' => $odds,
            'type' => $eventsCount > 1 ? 'multi' : 'simple',
            'status' => 'waiting_result',
        ], App\UserBet::class, $modifiers));

        $events = factory(App\Bets\Bets\Events\BetslipEvent::class, $eventsCount)->make(
            $this->applyModifiers([], App\UserBetEvent::class, $modifiers)
        );

        if (!is_a($events, Illuminate\Database\Eloquent\Collection::class)) {
            $events = collect([$events]);
        }

        $events->each(function ($event) use ($bet) {
            $bet->events->add($event);
        });

        return $bet;
    }

    protected function placeBet($bet)
    {
        BetBookie::placeBet($bet);
    }

    protected function placeBetForUser($userId, $amount, $odds = 1.5, $modifiers = [], $eventsCount = 1)
    {
        $bet = $this->makeBetForUser($userId, $amount, $odds, $modifiers, $eventsCount);

        $this->placeBet($bet);

        return $bet->fresh();
    }

    protected function resultBetAsWin(Bet $bet)
    {
        $bet->events->each(function ($event) {
            $event->status = "won";

            $event->save();
        });

        BetBookie::wonResult($bet);
    }

    protected function resultBetAsLost(Bet $bet)
    {
        $bet->events->each(function ($event) {
            $event->status = "lost";

            $event->save();
        });

        BetBookie::lostResult($bet);
    }

    protected function resultBetAsReturned(Bet $bet)
    {
        $bet->events->each(function ($event) {
            $event->status = "returned";

            $event->save();
        });

        BetBookie::returnBet($bet);
    }

    protected function assertBetAmountCharge($bet, $amount)
    {
        $this->assertTrue(round($bet->transactions->first()->amount_balance * 1, 2) === round($amount, 2));
    }

    protected function assertBetBonusCharge($bet, $amount)
    {
        $this->assertTrue(round($bet->transactions->first()->amount_bonus * 1, 2) === round($amount, 2));
    }

    protected function assertBetAmountAwardedIsCorrect($bet, $amount = null)
    {
        $amount = round(is_null($amount) ? $bet->transactions->first()->amount_amount * 1 * $bet->odd : $amount, 2);

        $this->assertTrue(round($bet->transactions->last()->amount_balance * 1, 2) === $amount);
    }

    protected function assertBetBonusAwardedIsCorrect($bet, $amount = null)
    {
        $amount = round(is_null($amount) ? $bet->transactions->first()->amount_bonus * 1 * $bet->odd : $amount, 2);

        $this->assertTrue(round($bet->transactions->last()->amount_bonus * 1, 2) === $amount);
    }

    protected function assertBalanceOfUser($user, $amount)
    {
        $this->assertTrue(
            round($user->balance->fresh()->balance_available * 1, 2) ===
            round($amount, 2)
        );
    }

    protected function assertBonusOfUser($user, $amount)
    {
        $this->assertTrue(
            round($user->balance->fresh()->balance_bonus * 1, 2) ===
            round($amount, 2)
        );
    }

    protected function setUserBalance($user, $amount)
    {
        $balance = $user->balance = $user->balance->fresh();

        $diffAmount = $amount - $balance->balance_available;

        if ($diffAmount > 0) {
            $balance->addAvailableBalance($diffAmount);
        } else {
            $balance->subtractAvailableBalance($diffAmount * -1);
        }
    }

    protected function setUserBonus($user, $amount)
    {
        $balance = $user->balance = $user->balance->fresh();

        $diffAmount = $amount - $balance->balance_bonus;

        if ($diffAmount > 0) {
            $balance->addBonus($diffAmount);
        } else {
            $balance->subtractBonus($diffAmount * -1);
        }
    }

    protected function betIsChargeable($bet)
    {
        return (new ChargeCalculator($bet, $this->bonusFacade::applicableTo($bet)))->chargeable;
    }

    protected function assertBetIsChargeable($bet)
    {
        $this->assertTrue($this->betIsChargeable($bet));
    }

    protected function assertBetIsNotChargeable($bet)
    {
        $this->assertFalse($this->betIsChargeable($bet));
    }

    protected function createWithdrawalFromUserAccount($userId, $amount)
    {
        $trans = UserTransaction::createTransaction(
            $amount,
            $userId,
            'paypal',
            'withdrawal',
            null,
            UserSession::getSessionId()
        );
    }

    protected function deleteBet($bet)
    {
        $bet->transactions()->delete();
        $bet->statuses()->delete();
        $bet->events()->delete();
        $bet->delete();
    }

    protected function redeem($bonusId = null)
    {
        $this->bonusFacade::redeem($bonusId ?: $this->bonus->id);
    }

    protected function assertBonusPreview($amount)
    {
        $this->assertTrue(round($this->bonusFacade::previewRedeemAmount($this->bonus), 2) === round($amount, 2));
    }
}
