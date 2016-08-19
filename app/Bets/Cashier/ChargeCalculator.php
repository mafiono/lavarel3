<?php


namespace App\Bets\Cashier;


use App\Bets\Bets\Bet;
use App\GlobalSettings;

class ChargeCalculator
{
    private $bet;

    private $amountBalance = 0;
    private $amountBonus = 0;
    private $amountTax = 0;

    private $tax;

    private $balanceSplit;
    private $bonusSplit;

    private $chargeable = false;

    public function __construct(Bet $bet, $usesBonus = true)
    {
        $this->bet = $bet;

        $this->tax = GlobalSettings::getTax();

        $this->balanceSplit = GlobalSettings::getBalanceSplit();

        $this->bonusSplit = GlobalSettings::getBonusSplit();

        if ($usesBonus)
            $this->compute();
        else
            $this->computeWithoutBonus();
    }

    public function chargeable()
    {
        return $this->chargeable;
    }

    public function getBalanceAmount()
    {
        return $this->amountBalance;
    }

    public function getBonusAmount()
    {
        return $this->amountBonus;
    }

    public function getTaxAmount()
    {
        return $this->amountTax;
    }

    public function computeWithoutBonus()
    {
        $hasEnoughBalance = ($this->bet->amount * (1 + $this->tax)) <= $this->bet->user->balance->balance_available;

        $this->amountBalance = $this->bet->amount;

        $this->amountTax = $this->amountBalance * $this->tax;

        $this->chargeable = $hasEnoughBalance;
    }

    private function compute()
    {
        $hasEnoughBalance = ($this->bet->amount * $this->balanceSplit * (1 + $this->tax)) <= $this->bet->user->balance->balance_available;

        $hasEnoughBonus = ($this->bet->amount * $this->bonusSplit) <= $this->bet->user->balance->balance_bonus;

        if ($hasEnoughBalance && $hasEnoughBonus) {

            $this->amountBalance = $this->bet->amount * $this->balanceSplit;
            $this->amountBonus = $this->bet->amount - $this->amountBalance;

        } else if ($hasEnoughBalance) {

            $this->amountBonus = $this->bet->user->balance->balance_bonus * 1;
            $this->amountBalance = $this->bet->amount - $this->amountBonus;

        } else if ($hasEnoughBonus) {

            $this->amountBalance = $this->bet->user->balance->balance_available * (1 - $this->tax);
            $this->amountBonus = $this->bet->amount - $this->amountBalance;

        } else
            return;

        $this->amountTax = $this->amountBalance * $this->tax;

        $this->chargeable = ($this->amountBalance + $this->amountTax) <= $this->bet->user->balance->balance_available
            && $this->amountBonus <= $this->bet->user->balance->balance_bonus;
    }
}