<?php

namespace App\Bets\Cashier;


use app\Bets\Bets\Bet;
use App\UserBonus;

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

    public static function charge(Bet $bet, $taxBet = null)
    {
        $receipt = BetCashierReceipt::makeWithdrawal($bet);

        $amountBonus = 0;

        if (UserBonus::canUseBonus($bet)) {

            $amountBonus = min($bet->amount, $bet->user->balance->balance_bonus);

            $bet->user->balance->subtractBonus($amountBonus);

            $bet->user->activeBonus->addWageredBonus($amountBonus);
        }

        $amountBalance = $bet->amount - $amountBonus + ($taxBet?$bet->amount_taxed:0);

        $bet->user->balance->subtractAvailableBalance($amountBalance);

        $receipt->amount_balance = $amountBalance;
        $receipt->amount_bonus = $amountBonus;

        $receipt->store();
    }

    public static function refund(Bet $bet)
    {
        $receipt = new BetCashierReceipt($bet);

        $receipt->prepare('deposit');

        $placeBetTransaction = $bet->firstStatus->transaction;
        $amountBonus =  $placeBetTransaction->amount_bonus;
        $amountBalance = $placeBetTransaction->amount_balance;

        $bet->user->balance->addBonus($amountBonus);
        $bet->user->balance->addAvailableBalance($amountBalance);

        $receipt->finalize($amountBalance, $amountBonus);

        return $receipt;
    }

    private static function rolloverCriteria(Bet $bet)
    {
        if ($bet->user->activeBonus->rolloverCriteriaReached())
            $bet->user->balance->moveBonusToAvailable();
    }

}