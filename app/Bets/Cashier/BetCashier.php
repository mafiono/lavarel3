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
            self::rolloverCriteria($bet); //Temporary
        }

        if ($amountBalance)
            $bet->user->balance->addAvailableBalance($amountBalance);

        $receipt->amount_balance = $amountBalance;
        $receipt->amount_bonus = $amountBonus;

        $receipt->store();
    }

    public static function noPay(Bet $bet)
    {
        BetCashierReceipt::makeDeposit($bet)->store();
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

        $bet->amount_taxed = $amountTax;
        $bet->save();
    }

    public static function refund(Bet $bet)
    {
        $receipt = BetCashierReceipt::makeDeposit($bet);

        $transaction = $bet->waitingResultStatus->transaction;

        $amountBonus =  $transaction->amount_bonus;
        $amountBalance = $transaction->amount_balance;

        $bet->user->balance->addBonus($amountBonus);
        $bet->user->balance->addAvailableBalance($amountBalance);

        $receipt->store();
    }

    private static function rolloverCriteria(Bet $bet)
    {
        if ($bet->user->activeBonus->rolloverCriteriaReached())
            $bet->user->balance->moveBonusToAvailable();
    }

}