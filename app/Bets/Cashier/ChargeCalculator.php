<?php

namespace App\Bets\Cashier;

use App\Bets\Bets\Bet;
use App\GlobalSettings;
use SportsBonus;

class ChargeCalculator
{
    public $balanceAmount = 0;

    public $bonusAmount = 0;

    public $chargeable = false;

    protected $betAmount;

    protected $balanceAvailable;

    protected $balanceBonus;

    public function __construct(Bet $bet, $useBonus = true)
    {
        $this->betAmount = $bet->amount *1;

        $balance = $bet->user->balance->fresh();

        $this->balanceAvailable = $balance->balance_available * 1;

        $this->balanceBonus = $balance->balance_bonus * 1;

        if ($useBonus) {
            switch (SportsBonus::getBonusType()) {
                case 'first_deposit':
                    $this->computeForFirstDepositBonus();
                    break;
                case 'first_bet':
                    $this->computeForFirsDepositBetBonus();
                    break;
            }
        } else {
            $this->computeWithoutBonus();
        }
    }

    protected function computeForFirstDepositBonus()
    {
        $this->balanceAmount = $this->betAmount * GlobalSettings::getFirstDepositBalanceSplit();

        $this->bonusAmount = $this->betAmount * GlobalSettings::getFirstDepositBonusSplit();

        $this->chargeable = $this->balanceAmount <= $this->balanceAvailable
            && $this->bonusAmount <= $this->balanceBonus;
    }

    protected function computeForFirsDepositBetBonus()
    {
        $totalBalance = $this->balanceAvailable + $this->balanceBonus;

        $this->balanceAmount = min($this->balanceAvailable, $this->betAmount);

        $this->bonusAmount = $this->betAmount - $this->balanceAmount;

        $this->chargeable = $this->balanceAmount < 2
            && $totalBalance >= ($this->balanceAmount + $this->bonusAmount);
    }

    protected function computeWithoutBonus()
    {
        $this->balanceAmount = $this->betAmount;

        $this->chargeable = $this->balanceAvailable >= $this->betAmount;
    }
}
