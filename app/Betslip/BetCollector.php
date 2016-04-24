<?php
namespace App\Betslip;
use App\UserBonus;

class BetCollector {

    public static function pay($user, $amount)
    {

    }

    public static function charge($bet)
    {
        $user = $bet->getUser();
        $balances = [
            'initial_balance' => $user->balance->balance_available,
            'initial_bonus' => $user->balance->balance_bonus
        ];



        $bonusCharge = min($bet->getAmount(), $user->balance->balance_bonus);
        $user->balance->subtractBonus($bonusCharge);

        $balanceCharge = $bet->getAmount() - $bonusCharge;
        $user->balance->subtractAvailableBalance($balanceCharge);

        $balances['amount_bonus'] = $bonusCharge;
        $balances['amount'] = $balanceCharge;
        $balances['final_balance'] = $user->balance->balance_available;
        $balances['final_bonus'] = $user->balance->balance_bonus;

        return $balances;
    }
}