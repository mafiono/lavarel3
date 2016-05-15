<?php

namespace App\Bets\Cashier;

use App\UserBet;
use App\UserBonus;


class BetCashier
{
    /**
     * @param UserBet $bet
     * @return BetCashierReceipt
     */
    public static function pay(UserBet $bet)
    {
        $receipt = new BetCashierReceipt($bet);

        $receipt->prepare('deposit');

        $placeBetTransaction = $bet->waitingResultStatus->transaction;
        $amountBonus =  $placeBetTransaction->amount_bonus*$bet->odd;
        $amountBalance = $placeBetTransaction->amount_balance*$bet->odd;

        if ($amountBonus) {
            $bet->user->balance->addBonus($amountBonus);
            self::rolloverCriteria($bet); //Temporary
        }

        if ($amountBalance)
            $bet->user->balance->addAvailableBalance($amountBalance);

        $receipt->finalize($amountBalance, $amountBonus);

        return $receipt;
    }

    /**
     * @param UserBet $bet
     * @param bool $tax
     * @return BetCashierReceipt
     */
    public static function charge(UserBet $bet, $taxBet = false)
    {
        $receipt = new BetCashierReceipt($bet);

        $receipt->prepare('withdrawal');

        $amountBonus = 0;
        if (UserBonus::canUseBonus($bet)) {
            $amountBonus = min($bet->amount, $bet->user->balance->balance_bonus);

            $bet->user->balance->subtractBonus($amountBonus);

            $bet->user->activeBonus->addWageredBonus($amountBonus);
        }

        $amountBalance = $bet->amount - $amountBonus + ($taxBet?$bet->amount_taxed:0);

        $bet->user->balance->subtractAvailableBalance($amountBalance);

        $receipt->finalize($amountBalance, $amountBonus);

        return $receipt;
    }

    /**
     * @param UserBet $bet
     * @return BetCashierReceipt
     */
    public static function refund(UserBet $bet)
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

    /**
     * @param UserBet $bet
     */
    private static function rolloverCriteria(UserBet $bet)
    {
        if ($bet->user->activeBonus->rolloverCriteriaReached())
            $bet->user->balance->moveBonusToAvailable();
    }

}