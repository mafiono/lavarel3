<?php

namespace App\Bets\Cashier;


use App\Bets\Bets\Bet;
use App\UserTransaction;
use SportsBonus;


class BetCashier
{

    public static function pay(Bet $bet)
    {
        $receipt = BetCashierReceipt::makeDeposit($bet);

        $transaction = $bet->waitingResultStatus->transaction;

        $amountBonus =  $transaction->amount_bonus*$bet->odd;

        $amountBalance = $transaction->amount_balance*$bet->odd;

        if ($amountBonus)
            $bet->user->balance->addBonus($amountBonus);

        if ($amountBalance)
            $bet->user->balance->addAvailableBalance($amountBalance);

        $receipt->amount_balance = $amountBalance;
        $receipt->amount_bonus = $amountBonus;

        $receipt->store();

        if (SportsBonus::isPayable())
            SportsBonus::pay($bet->bonus_id);
    }

    public static function noPay(Bet $bet)
    {
        BetCashierReceipt::makeDeposit($bet)->store();

        if ($bet->bonus_id && SportsBonus::isAutoCancellable($bet->bonus_id))
            SportsBonus::cancel($bet->bonus_id);

        if (SportsBonus::isPayable())
            SportsBonus::pay($bet->bonus_id);
    }

    public static function charge(Bet $bet)
    {
        $receipt = BetCashierReceipt::makeWithdrawal($bet);

        $bill = new ChargeCalculator($bet, SportsBonus::applicableTo($bet));

        $amountBalance = $bill->getBalanceAmount();
        $amountTax = $bill->getTaxAmount();
        $amountBonus = $bill->getBonusAmount();

        $bet->user->balance->subtractAvailableBalance($amountBalance + $amountTax);
        $bet->user->balance->subtractBonus($amountBonus);

        $receipt->amount_balance = $amountBalance;
        $receipt->amount_bonus = $amountBonus;

        $receipt->store();

        if ($amountBonus) {
            $bet->bonus_id = SportsBonus::getActive()->id;
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
        $amountBalance = $transaction->amount_balance;

        $bet->user->balance->addBonus($amountBonus);
        $bet->user->balance->addAvailableBalance($amountBalance + $bet->amountTaxed);

        $receipt->store();

        if ($amountBonus) {
            SportsBonus::subtractWagered($amountBonus);

            $bet->user->balance = $bet->user->balance->fresh();
        }

        if (SportsBonus::isPayable())
            SportsBonus::pay($bet->bonus_id);
    }

}