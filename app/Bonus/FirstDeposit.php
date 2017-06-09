<?php

namespace App\Bonus;

use App\Bets\Bets\Bet;
use App\GlobalSettings;
use App\UserTransaction;
use Carbon\Carbon;
use Session;

class FirstDeposit extends BaseSportsBonus
{
    public function deposit()
    {
        $trans = UserTransaction::whereUserId($this->user->id)
            ->whereStatusId('processed')
            ->latest('id')
            ->first();

        $bonusAmount = min(
            $trans->debit * $this->userBonus->bonus->value * 0.01,
            GlobalSettings::maxFirstDepositBonus()
        );

        $this->user->balance->addBonus($bonusAmount);

        $this->userBonus->update([
            'bonus_value' => $bonusAmount,
            'deposited' => 1,
        ]);

        UserTransaction::forceCreate([
            'user_id' => $this->user->id,
            'origin' => 'sport_bonus',
            'transaction_id' => UserTransaction::getHash($this->user, Carbon::now()),
            'debit' => $bonusAmount,
            'initial_balance' => $this->user->balance->balance_available,
            'final_balance' => $this->user->balance->balance_available + $bonusAmount,
            'date' => Carbon::now(),
            'description' => 'Resgate de bónus ' . $this->userBonus->bonus->title,
        ]);
    }

    public function applicableTo(Bet $bet)
    {
        return ($bet->type === 'multi')
            && parent::applicableTo($bet)
            && ($bet->events->count() > 2)
            && $this->hasAllEventsAboveMinOdds($bet);
    }

    protected function hasAllEventsAboveMinOdds($bet)
    {
        return $bet->events->filter(function ($event) {
            return $event->odd < GlobalSettings::getFirstDepositEventMinOdds();
        })->isEmpty();
    }

    public function isAutoCancellable()
    {
        return $this->user->balance->fresh()->balance_bonus*1 < 0.2
            && parent::isAutoCancellable();
    }
}
