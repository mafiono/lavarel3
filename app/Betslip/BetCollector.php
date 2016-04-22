<?php
namespace App\Betslip;
use App\UserBonus;

class BetCollector {

    public static function pay($user, $amount) {

    }

    public static function charge($bet) {
        $user = $bet->getUser();
        $balances = [
            'initial_balance' => $user->balance->balance_available,
            'initial_bonus' => $user->balance->balance_bonus
        ];

        if (UserBonus::hasActiveBonus($user->id)) {
            $user->balance->subtractBonus($bet->getAmount());
        } else {
            $user->balance->subtractAvailableBalance($bet->getAmount());
        }

        $balances['final_balance'] = $user->balance->balance_available;
        $balances['final_bonus'] = $user->balance->balance_bonus;

        return $balances;

    }
}