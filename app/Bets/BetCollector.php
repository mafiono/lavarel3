<?php

namespace App\Bets;

use App\UserBet;
use App\UserBonus;

/**
 * Class BetCollector
 * @package App\Bets
 */
class BetCollector
{

    /**
     * @param Bet $bet
     * @param $amount
     * @return array
     */
    public static function pay(Bet $bet, UserBet $userBet)
    {
        $user = $bet->getUser();

        $receipt = self::prepareReceipt($bet, $user);

        $trans = $userBet->firstStatus->transaction;
        $receipt['operation'] = "deposit";
        $amountBonus =  $trans->amount_bonus*$userBet->odd;
        $user->balance->addBonus($amountBonus);
        $amountBalance = $trans->amount_balance*$userBet->odd;
        $user->balance->addAvailableBalance($amountBalance);

        $receipt = self::finalizeReceipt($amountBonus, $receipt, $amountBalance, $user);

        return new CollectorReceipt($receipt);
    }


    /**
     * @param Bet $bet
     * @return CollectorReceipt
     */
    public static function charge(Bet $bet)
    {
        $user = $bet->getUser();

        $receipt = self::prepareReceipt($bet, $user);

        $receipt['operation'] = "withdrawal";

        $amountBonus = 0;
        if (UserBonus::canUseBonus($bet)) {
            $amountBonus = min($bet->getAmount(), $user->balance->balance_bonus);
            $user->balance->subtractBonus($amountBonus);
        }

        $amountBalance = $bet->getAmount() - $amountBonus;
        $user->balance->subtractAvailableBalance($amountBalance);

        $receipt = self::finalizeReceipt($amountBalance, $receipt, $amountBonus, $user);

        return new CollectorReceipt($receipt);
    }

    public static function refund(Bet $bet, UserBet $userBet) {
        $user = $bet->getUser();

        $trans = $userBet->firstStatus->transaction;
        $receipt['operation'] = "deposit";
        $amountBonus =  $trans->amount_bonus;
        $user->balance->addBonus($amountBonus);
        $amountBalance = $trans->amount_balance;
        $user->balance->addAvailableBalance($amountBalance);

        $receipt = self::finalizeReceipt($amountBalance, $receipt, $amountBonus, $user);

        return new CollectorReceipt($receipt);
    }

    /**
     * @param Bet $bet
     * @param $user
     * @return array
     */
    private static function prepareReceipt(Bet $bet, $user)
    {
        $receipt = [
            'owner_id' => $user->id,
            'api_transaction_id' => $bet->getApiTransactionId(),
            'initial_balance' => $user->balance->balance_available,
            'initial_bonus' => $user->balance->balance_bonus
        ];
        return $receipt;
    }

    /**
     * @param $amountBalance
     * @param $receipt
     * @param $amountBonus
     * @param $user
     * @return mixed
     */
    private static function finalizeReceipt($amountBalance, $receipt, $amountBonus, $user)
    {
        $receipt['amount_balance'] = $amountBalance;
        $receipt['amount_bonus'] = $amountBonus;
        $receipt['final_balance'] = $user->balance->balance_available;
        $receipt['final_bonus'] = $user->balance->balance_bonus;
        return $receipt;
    }


}