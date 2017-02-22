<?php

namespace App\Bets\Cashier;

use App\Bets\Bets\Bet;
use App\GlobalSettings;
use SportsBonus;

class ChargeCalculator
{
    protected $bet;

    protected $betAmount;

    protected $balanceAvailable;

    protected $balanceBonus;

    protected $balanceAmount = 0;

    protected $bonusAmount = 0;

    protected $taxAmount = 0;

    protected $tax;

    protected $balanceSplit;

    protected $bonusSplit;

    protected $chargeable = false;

    public function __construct(Bet $bet, $useBonus = true)
    {
        $this->bet = $bet;

        $this->betAmount = $this->bet->amount *1;

        $this->balanceAvailable = $this->bet->user->balance->balance_available * 1;

        $this->balanceBonus = $this->bet->user->balance->balance_bonus * 1;

        $this->tax = GlobalSettings::getTax();

        $this->balanceSplit = GlobalSettings::getBalanceSplit();

        $this->bonusSplit = GlobalSettings::getBonusSplit();

        $this->compute($useBonus);
    }

    public function chargeable()
    {
        return $this->chargeable;
    }

    public function getBalanceAmount()
    {
        return $this->balanceAmount;
    }

    public function getBonusAmount()
    {
        return $this->bonusAmount;
    }

    public function getTaxAmount()
    {
        return $this->taxAmount;
    }

    protected function compute($useBonus)
    {
        if ($useBonus) {
            switch (SportsBonus::getBonusType()) {
                case 'first_deposit':
                case 'deposits':
                    $this->computeWithDepositsBonus();
                    break;
                case 'friend_invite':
                case 'free_bet':
                    $this->computeWithFreeBet();
                    break;
            }
        } else {
            $this->computeWithoutBonus();
        }
    }

    protected function computeWithoutBonus()
    {
        $this->balanceAmount = $this->betAmount;

        $this->taxAmount = $this->balanceAmount * $this->tax;

        $this->chargeable = $this->balanceAvailable >= ($this->balanceAmount + $this->taxAmount);
    }

    protected function computeWithDepositsBonus()
    {
        $balanceAmount = $this->betAmount * $this->balanceSplit;

        $bonusAmount = $this->betAmount - $balanceAmount;

        $taxAmount = $balanceAmount * $this->tax;

        $hasEnoughBalance = $this->balanceAvailable >= ($balanceAmount + $taxAmount);

        $hasEnoughBonus = $this->balanceBonus >= $bonusAmount;

        if ($hasEnoughBalance && $hasEnoughBonus) {
            $this->balanceAmount = $balanceAmount;
            $this->bonusAmount = $bonusAmount;

        } elseif ($hasEnoughBalance) {
            $this->bonusAmount = $this->balanceBonus;
            $this->balanceAmount = $this->betAmount - $this->bonusAmount;

        } elseif ($hasEnoughBonus) {
            $this->balanceAmount = $this->balanceAvailable * (1 - $this->tax);
            $this->bonusAmount = $this->betAmount - $this->balanceAmount;

        } else {
            return;

        }
        $this->taxAmount = $this->balanceAmount * $this->tax;

        $this->chargeable = $this->balanceAvailable >= ($this->balanceAmount + $this->taxAmount)
            && $this->balanceBonus >= $this->bonusAmount;
    }

    protected function computeWithFreeBet()
    {
        $hasEnoughBonus = $this->balanceBonus >= $this->betAmount;

        if ($hasEnoughBonus) {
            $this->bonusAmount = $this->betAmount;

        } else {
            $this->bonusAmount = $this->balanceBonus;
            $this->balanceAmount = $this->betAmount - $this->bonusAmount;

        }

        $this->taxAmount = $this->balanceAmount * $this->tax;

        $this->chargeable = $this->balanceAvailable >= ($this->balanceAmount + $this->taxAmount)
            && $this->balanceBonus >= $this->bonusAmount;
    }
}
