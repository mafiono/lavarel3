<?php

namespace App\Bonus;

use App\Bets\Bets\Bet;
use App\GlobalSettings;
use App\UserBet;
use App\UserTransaction;

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
