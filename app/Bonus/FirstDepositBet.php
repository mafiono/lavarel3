<?php

namespace App\Bonus;

use App\Bets\Bets\Bet;
use App\GlobalSettings;
use App\UserBet;
use App\UserTransaction;
use Carbon\Carbon;

class FirstDepositBet extends BaseSportsBonus
{

    public function isPayable()
    {
        return $this->userBonus->deposited === 1
            && $this->userBonus->bonus_wagered >= $this->userBonus->rollover_amount
            && !$this->hasUnresolvedBets();
    }

    public function applicableTo(Bet $bet)
    {
        return ($bet->type === 'multi')
            && parent::applicableTo($bet)
            && !$this->hasUnresolvedBets([$bet->id]);
    }

    public function deposit()
    {
        $firstBet = UserBet::firstBetFomUser($this->user->id)
            ->notReturned()
            ->first();

        $bonusAmount = min($firstBet->amount * 0.5, $this->userBonus->bonus->max_bonus);

        $initial_bonus = $this->user->balance->balance_bonus;
        $this->user->balance->addBonus($bonusAmount);

        $this->userBonus->bonus_value = $bonusAmount;
        $this->userBonus->deposited = 1;
        $this->userBonus->save();

        UserTransaction::forceCreate([
            'user_id' => $this->user->id,
            'origin' => 'sport_bonus',
            'transaction_id' => UserTransaction::getHash($this->user->id, Carbon::now()),
            'debit_bonus' => $bonusAmount,
            'initial_balance' => $this->user->balance->balance_available,
            'initial_bonus' => $initial_bonus,
            'final_balance' => $this->user->balance->balance_available,
            'final_bonus' => $this->user->balance->balance_bonus,
            'date' => Carbon::now(),
            'description' => 'Resgate de bónus ' . $this->userBonus->bonus->title,
            'status_id' => 'processed',
        ]);
    }

    public function isAutoCancellable()
    {
        return $this->user->balance->fresh()->balance_bonus*1 == 0
            && parent::isAutoCancellable();

    }
}
