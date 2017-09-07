<?php

use App\Models\CasinoGame;
use App\Models\CasinoRound;
use App\Models\CasinoSession;
use App\Models\CasinoToken;
use Carbon\Carbon;

class CasinoNoDepositTest extends BaseBonusTest
{
    protected $bonusFacade = 'CasinoBonus';

    protected $deadline = 10;

    protected $depositAmount = 100;

    protected $rolloverCoefficient = 30;

    protected $bonusAmount = 50;

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
            'bonus_type_id' => 'casino_no_deposit',
            'min_odd' => 2.2,
            'value_type' => 'percentage',
            'deadline' => $this->deadline,
            'rollover_coefficient' => $this->rolloverCoefficient,
            'value' => $this->bonusAmount,
            'max_bonus' => 100,
        ]);

        $this->user->transactions()->delete();

        $this->bonus->depositMethods()->create([
            'deposit_method_id' => 'bank_transfer'
        ]);

        $this->bonus->targets()->create([
            'target_id' => 'Risk0'
        ]);

        $this->user->balance->addAvailableBalance(100);

        auth()->login($this->user->fresh());
    }

    public function testItIsAvailable()
    {
        $this->assertBonusAvailable();
    }

    public function testItIsAvailableAfterDeposit()
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

    public function testItAvailableWithoutUserTransactions()
    {
        $this->user->transactions()->delete();

        $this->assertBonusAvailable();
    }

    public function testItHasCorrectAttributesAfterRedeem()
    {
        CasinoBonus::redeem($this->bonus->id);

        $this->user->balance = $this->user->balance->fresh();

        $this->seeInDatabase('user_bonus', [
            'user_id' => $this->user->id,
            'bonus_id' => $this->bonus->id,
            'bonus_head_id' => $this->bonus->head_id,
            'user_transaction_id' => null,
            'active' => 1,
            'deposited' => 0,
            'bonus_value' => $this->bonusAmount,
            'rollover_amount' => $this->rolloverCoefficient * $this->bonusAmount,
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

    public function testItCanNotBeRedeemedAfterCancelled()
    {
        CasinoBonus::redeem($this->bonus->id);

        CasinoBonus::cancel();

        $this->setExpectedException(App\Bonus\Casino\CasinoBonusException::class);

        CasinoBonus::redeem($this->bonus->id);

        $this->assertHasNoActiveBonus();
    }

    public function testThatCancelFailsAfterItHasBeenCancelled()
    {
        CasinoBonus::redeem($this->bonus->id);

        CasinoBonus::cancel();

        $this->setExpectedException(App\Bonus\Casino\CasinoBonusException::class);

        CasinoBonus::cancel();
    }

    public function testCancelFailsIfUserIsSelfExcluded()
    {
        CasinoBonus::redeem($this->bonus->id);

        $this->user->status->update([
            'selfexclusion_status_id' => 'minimum_period',
        ]);

        $this->setExpectedException(App\Bonus\Casino\CasinoBonusException::class);

        CasinoBonus::refreshUser();

        CasinoBonus::cancel();
    }

    public function testItIsConsumedAfterCancelAndNotBefore()
    {
        CasinoBonus::redeem($this->bonus->id);

        $this->assertBonusWasNotConsumed();

        CasinoBonus::cancel();

        $this->assertBonusWasConsumed();
    }

    public function testRedeemShouldFailWhenAnotherBonusIsActive()
    {
        CasinoBonus::redeem($this->bonus->id);

        $this->setExpectedException(App\Bonus\Casino\CasinoBonusException::class);

        $newBonus = $this->createBonus([
            'bonus_type_id' => 'casino_deposit',
            'min_odd' => 1.2,
            'value_type' => 'percentage',
            'deadline' => 4,
            'rollover_coefficient' => 2,
            'value' => 100,
        ]);

        CasinoBonus::redeem($newBonus->id);
    }

    public function testItIgnoresMinDeposit()
    {
        factory(App\UserTransaction::class)->create([
            'user_id' => $this->user->id,
            'status_id' => 'processed',
            'debit' => '100',
            'origin' => 'bank_transfer'
        ]);

        $this->bonus->update([
            'min_deposit' => 20,
        ]);

        $this->assertBonusAvailable($this->bonus->id);
    }

    public function testItAwardsTheCorrectBonusAmount()
    {
        CasinoBonus::redeem($this->bonus->id);

        $this->assertBonusOfUser($this->user, $this->bonusAmount);
    }

    public function testItIgnoresMaxBonus()
    {
        $this->bonus->update(['max_bonus' => 5]);

        CasinoBonus::redeem($this->bonus->id);

        $this->assertBonusOfUser($this->user, $this->bonusAmount);
    }

    public function testThatTheAmountOfBonusAfterCancelIsZero()
    {
        CasinoBonus::redeem($this->bonus->id);

        CasinoBonus::cancel();

        $this->assertBonusOfUser($this->user, 0);
    }

    public function testItAutoCancelsAfterDeadlineDate()
    {
        CasinoBonus::redeem($this->bonus->id);

        CasinoBonus::userBonus()->update([
            'deadline_date' => Carbon::now()->subHour(2)
        ]);

        Artisan::call('cancel-bonuses');

        CasinoBonus::swapUser($this->user);

        $this->assertBonusWasConsumed($this->bonus->id);
    }

    public function testItDoesntAutoCancelsAfterDeadlineDateIfHasActiveRounds()
    {
        $game = CasinoGame::create([
            'id' => 123,
            'type_id' => 'cards'
        ]);

        $token = CasinoToken::create([
            'user_id' => $this->user->id,
            'user_session_id' => $this->user->sessions->first()->id,
        ]);

        $casinoSession = CasinoSession::create([
            'user_id' => $this->user->id,
            'game_id' => $game->id,
            'token_id' => $token->id,
        ]);

        CasinoBonus::redeem($this->bonus->id);

        CasinoRound::create([
            'user_id' => $this->user->id,
            'session_id' => $casinoSession->id,
            'game_id' => $game->id,
            'roundstatus' => 'active',
            'user_bonus_id' => CasinoBonus::userBonus()->id,
        ]);

        CasinoBonus::userBonus()->update([
            'deadline_date' => Carbon::now()->subHour(2)
        ]);

        Artisan::call('cancel-bonuses');

        CasinoBonus::swapUser($this->user);

        $this->assertHasActiveBonus();
    }

    public function testIfUserMakesWithdrawalWithoutActiveRoundsThenBonusIsCancelled()
    {
        CasinoBonus::redeem($this->bonus->id);

        $this->createWithdrawalFromUserAccount($this->user->id, 25);

        $this->assertBonusWasConsumed();
    }

    public function testItCreatesUserTransactionWhenRedeemed()
    {
        CasinoBonus::redeem($this->bonus->id);

        $balance = $this->user->balance;

        $this->seeInDatabase('user_transactions', [
            'user_id' => $this->user->id,
            'origin' => 'casino_bonus',
            'debit_bonus' => number_format($this->bonusAmount, 2),
            'initial_balance' => number_format($balance->balance_available, 2),
            'final_balance' => number_format($balance->balance_available, 2),
            'initial_bonus' => number_format($balance->balance_bonus, 2),
            'final_bonus' => number_format($balance->balance_bonus + $this->bonusAmount, 2),
            'description' => 'Resgate de bónus ' . $this->bonus->title,
        ]);
    }

    public function testItCreatesUserTransactionWhenEnds()
    {
        CasinoBonus::redeem($this->bonus->id);

        CasinoBonus::cancel();

        $balance = $this->user->balance;

        $this->seeInDatabase('user_transactions', [
            'user_id' => $this->user->id,
            'origin' => 'casino_bonus',
            'credit_bonus' => number_format($this->bonusAmount, 2),
            'initial_balance' => number_format($balance->balance_available, 2),
            'final_balance' => number_format($balance->balance_available, 2),
            'initial_bonus' => number_format($this->bonusAmount, 2),
            'final_bonus' => number_format(0, 2),
            'description' => 'Término de bónus ' . $this->bonus->title,
        ]);
    }

    public function testItCanBeRedeemedWithoutDepositMethodsSelected()
    {
        $this->bonus->depositMethods()->delete();

        $this->assertBonusAvailable();
    }

    public function testItCanManyAvailable()
    {
        $newBonus = $this->createBonus([
            'bonus_origin_id' => 'casino',
            'bonus_type_id' => 'casino_no_deposit',
            'min_odd' => 1.2,
            'value_type' => 'percentage',
            'deadline' => 4,
            'rollover_coefficient' => 2,
            'value' => 100,
        ]);

        $newBonus->targets()->create([
            'target_id' => 'Risk0'
        ]);

        $this->assertBonusAvailable($this->bonus->id);

        $this->assertBonusAvailable($newBonus->id);
    }

    public function testItCanBeAvailableAmongBonusThatRequireDeposits()
    {
        factory(App\UserTransaction::class)->create([
            'user_id' => $this->user->id,
            'status_id' => 'processed',
            'debit' => '100',
            'origin' => 'bank_transfer'
        ]);

        $newBonus = $this->createBonus([
            'bonus_origin_id' => 'casino',
            'bonus_type_id' => 'casino_deposit',
            'min_odd' => 1.2,
            'value_type' => 'percentage',
            'deadline' => 4,
            'rollover_coefficient' => 2,
            'value' => 100,
        ]);

        $newBonus->depositMethods()->create([
            'deposit_method_id' => 'bank_transfer'
        ]);

        $newBonus->targets()->create([
            'target_id' => 'Risk0'
        ]);

        $this->assertBonusAvailable($this->bonus->id);

        $this->assertBonusAvailable($newBonus->id);
    }

    public function testAnotherCasDepositBonusIsAvailableIfWasUsedOnSameDeposit()
    {
        $this->deposit = factory(App\UserTransaction::class)->create([
            'user_id' => $this->user->id,
            'status_id' => 'processed',
            'debit' => '100',
            'origin' => 'bank_transfer'
        ]);

        $newBonus = $this->createBonus([
            'bonus_origin_id' => 'casino',
            'bonus_type_id' => 'casino_deposit',
            'min_odd' => 1.2,
            'value_type' => 'percentage',
            'deadline' => 4,
            'rollover_coefficient' => 2,
            'value' => 100,
        ]);

        CasinoBonus::redeem($this->bonus->id);

        CasinoBonus::cancel();

        $newBonus->depositMethods()->create([
            'deposit_method_id' => 'bank_transfer'
        ]);

        $newBonus->targets()->create([
            'target_id' => 'Risk0'
        ]);

        $this->assertBonusAvailable($newBonus->id);
    }

    public function testItIsNotAvailableIfNoTargetsOrUsernamesAreSelected()
    {
        $this->bonus->targets()->delete();

        $this->assertBonusNotAvailable();
    }

    public function testItIsAvailableIfOnlyTheUsernameIsTargeted()
    {
        $this->bonus->targets()->delete();

        $this->bonus->usernameTargets()->create([
            'username' => $this->user->username
        ]);

        $this->assertBonusAvailable();
    }

    public function testItIsAvailableForMultipleUsersIfMultipleUsernamesAreTargeted()
    {
        $this->bonus->targets()->delete();

        $this->bonus->usernameTargets()->create([
            'username' => $this->user->username
        ]);

        $this->assertBonusAvailable();

        $newUser = $this->createUserWithEverything([
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

        $newUser->balance->addAvailableBalance(100);

        auth()->login($newUser);

        CasinoBonus::swapUser($newUser);

        $this->assertBonusNotAvailable();

        $this->bonus->usernameTargets()->create([
            'username' => $newUser->username
        ]);

        $this->assertBonusAvailable();
    }

    public function testBonusAmountPreviewIsCorrect()
    {
        $this->assertBonusPreview(50);
    }

    public function testItIsAvailableAfterSportsBonusWasUsedOnSameDeposit()
    {
        $this->deposit = factory(App\UserTransaction::class)->create([
            'user_id' => $this->user->id,
            'status_id' => 'processed',
            'debit' => '100',
            'origin' => 'bank_transfer'
        ]);

        $anotherBonus = $this->createBonus([
            'bonus_type_id' => 'first_deposit',
            'min_odd' => 1.2,
            'value_type' => 'percentage',
            'deadline' => 4,
            'rollover_coefficient' => 2,
            'value' => 100,
        ]);

        $anotherBonus->depositMethods()->create([
            'deposit_method_id' => 'bank_transfer'
        ]);

        $anotherBonus->targets()->create([
            'target_id' => 'Risk0'
        ]);

        SportsBonus::redeem($anotherBonus->id);

        SportsBonus::cancel();

        $this->assertBonusAvailable($this->bonus->id);
    }

    public function testSportsBonusAvailableAfterItWasRedeemOnSameDeposit()
    {
        $this->deposit = factory(App\UserTransaction::class)->create([
            'user_id' => $this->user->id,
            'status_id' => 'processed',
            'debit' => '100',
            'origin' => 'bank_transfer'
        ]);

        $anotherBonus = $this->createBonus([
            'bonus_type_id' => 'first_deposit',
            'min_odd' => 1.2,
            'value_type' => 'percentage',
            'deadline' => 4,
            'rollover_coefficient' => 2,
            'value' => 100,
        ]);

        $anotherBonus->depositMethods()->create([
            'deposit_method_id' => 'bank_transfer'
        ]);

        $anotherBonus->targets()->create([
            'target_id' => 'Risk0'
        ]);

        CasinoBonus::redeem($this->bonus->id);

        $this->assertBonusNotAvailable($anotherBonus->id, 'SportsBonus');

        CasinoBonus::cancel();

        SportsBonus::redeem($anotherBonus->id);

        $this->assertHasActiveBonus($anotherBonus->id, 'SportsBonus');
    }

    public function testIfUserMakesADepositWhileBonusIsStillActiveANewBonusWillBeAvailableAfterThePreviousIsCancelled()
    {
        $this->deposit = factory(App\UserTransaction::class)->create([
            'user_id' => $this->user->id,
            'status_id' => 'processed',
            'debit' => '100',
            'origin' => 'bank_transfer'
        ]);

        $newBonus = $this->createBonus([
            'bonus_origin_id' => 'casino',
            'bonus_type_id' => 'casino_deposit',
            'min_odd' => 1.2,
            'value_type' => 'percentage',
            'deadline' => 4,
            'rollover_coefficient' => 2,
            'value' => 100,
        ]);

        $newBonus->depositMethods()->create([
            'deposit_method_id' => 'bank_transfer'
        ]);

        $newBonus->targets()->create([
            'target_id' => 'Risk0'
        ]);

        $this->deposit->created_at = Carbon::now()->subSecond();
        $this->deposit->save();

        CasinoBonus::redeem($this->bonus->id);

        $userBonus = App\UserBonus::whereUserId($this->user->id)->first();
        $userBonus->created_at = Carbon::now()->subSecond();
        $userBonus->save();


        factory(App\UserTransaction::class)->create([
            'user_id' => $this->user->id,
            'status_id' => 'processed',
            'debit' => '100',
            'origin' => 'bank_transfer',
        ]);

        CasinoBonus::cancel();

        $this->assertBonusAvailable($newBonus->id);
    }

    public function testDepositBonusCanBeRedeemedAfterNoDepositInTheSameDeposit()
    {
        $this->deposit = factory(App\UserTransaction::class)->create([
            'user_id' => $this->user->id,
            'status_id' => 'processed',
            'debit' => '100',
            'origin' => 'bank_transfer'
        ]);

        $newBonus = $this->createBonus([
            'bonus_origin_id' => 'casino',
            'bonus_type_id' => 'casino_deposit',
            'min_odd' => 1.2,
            'value_type' => 'percentage',
            'deadline' => 4,
            'rollover_coefficient' => 2,
            'value' => 100,
        ]);

        $newBonus->depositMethods()->create([
            'deposit_method_id' => 'bank_transfer'
        ]);

        $newBonus->targets()->create([
            'target_id' => 'Risk0'
        ]);

        CasinoBonus::redeem($this->bonus->id);

        $this->assertBonusNotAvailable($newBonus->id);

        CasinoBonus::cancel();

        CasinoBonus::redeem($newBonus->id);

        $this->assertHasActiveBonus($newBonus->id);
    }

    public function testItIsntAvailableWhileOtherOfSameTypeIsActive()
    {
        $newBonus = $this->createBonus([
            'bonus_origin_id' => 'casino',
            'bonus_type_id' => 'casino_no_deposit',
            'min_odd' => 1.2,
            'value_type' => 'percentage',
            'deadline' => 4,
            'rollover_coefficient' => 2,
            'value' => 100,
        ]);

        $newBonus->targets()->create([
            'target_id' => 'Risk0'
        ]);

        CasinoBonus::redeem($this->bonus->id);

        $this->assertBonusNotAvailable($newBonus->id);

        CasinoBonus::cancel();

        $this->assertBonusAvailable($newBonus->id);
    }

}