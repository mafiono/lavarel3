<?php

namespace App\Bets\Cashier;

use App\Bets\Bets\Bet;
use SportsBonus;

class BetCashier
{

    public static function pay(Bet $bet)
    {
        $receipt = BetCashierReceipt::makeDeposit($bet);

        $transaction = $bet->waitingResultStatus->transaction;

        $amountBonus =  $transaction->amount_bonus*$bet->odd;

        $amountBalance = $transaction->amount_balance*$bet->odd;

        if ($amountBonus) {
            $bet->user->balance->addBonus($amountBonus);
        }

        if ($amountBalance) {
            $bet->user->balance->addAvailableBalance($amountBalance);
        }

        $receipt->amount_balance = $amountBalance;
        $receipt->amount_bonus = $amountBonus;

        $receipt->store();

        if (SportsBonus::isPayable()) {
            SportsBonus::pay();
        }
    }

    public static function noPay(Bet $bet)
    {

        BetCashierReceipt::makeDeposit($bet)->store();

        if (SportsBonus::isAutoCancellable()) {
            SportsBonus::cancel();
        }

        if (SportsBonus::isPayable()) {
            SportsBonus::pay();
        }
    }

    public static function charge(Bet $bet)
    {
        $receipt = BetCashierReceipt::makeWithdrawal($bet);

        $bill = new ChargeCalculator($bet, SportsBonus::applicableTo($bet));

        $amountBalance = $bill->getBalanceAmount();
        $amountTax = $bill->getTaxAmount();
        $amountBonus = $bill->getBonusAmount();

        $bet->user->balance->subtractAvailableBalance($amountBalance + $amountTax);

        $receipt->amount_balance = $amountBalance;
        $receipt->amount_bonus = $amountBonus;

        $receipt->store();

        if ($amountBonus) {
            if (SportsBonus::getBonusType() === 'free_bet') {
                $bet->user->balance->resetBonus();
            } else {
                $bet->user->balance->subtractBonus($amountBonus);
            }

            $bet->user_bonus_id = SportsBonus::userBonus()->id;
            $bet->save();

            SportsBonus::addWagered($amountBonus);
        }

        if ($amountTax) {
            $bet->amount_taxed = $amountTax;
            $bet->save();
        }
    }

    public static function refund(Bet $bet)
    {
        $receipt = BetCashierReceipt::makeDeposit($bet);

        $transaction = $bet->waitingResultStatus->transaction;

        $amountBonus =  $transaction->amount_bonus;
        $wageredBonus = $amountBonus;

        if ($amountBonus && SportsBonus::getBonusType() === 'free_bet') {
            $amountBonus = SportsBonus::userBonus()->bonus_value;
        }

        $amountBalance = $transaction->amount_balance;

        $bet->user->balance->addAvailableBalance($amountBalance + $bet->amountTaxed);

        $receipt->amount_balance = $amountBalance;
        $receipt->amount_bonus = $amountBonus;

        $receipt->store();

        if ($amountBonus) {
            $bet->user->balance->addBonus($amountBonus);

            SportsBonus::subtractWagered($wageredBonus);

            $bet->user->balance = $bet->user->balance->fresh();
        }

        if (SportsBonus::isPayable()) {
            SportsBonus::pay();
        }
    }
}
