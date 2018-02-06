<?php

namespace App\Bets\Cashier;

use App\Bets\Bets\Bet;
use App\GlobalSettings;
use SportsBonus;

class ChargeCalculator
{
    public $balanceAmount = 0.0;

    public $bonusAmount = 0.0;

    public $chargeable = false;

    protected $betAmount;

    protected $balanceAvailable;

    protected $balanceBonus;

    public function __construct(Bet $bet, $useBonus = true)
    {
        $this->betAmount = $bet->amount * 1.0;

        $balance = $bet->user->balance->fresh();

        $this->balanceAvailable = $balance->balance_available * 1.0;

        $this->balanceBonus = $balance->balance_bonus * 1.0;

        if ($useBonus) {
            switch (SportsBonus::getBonusType()) {
                case 'first_deposit':
                    $this->computeForFirstDepositBonus();
                    break;
                case 'first_bet':
                    $this->computeForFirsBetBonus();
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

    protected function computeForFirsBetBonus()
    {
        $this->bonusAmount = $this->betAmount;

        $this->chargeable = $this->balanceBonus === $this->betAmount;
    }

    protected function computeWithoutBonus()
    {
        $this->balanceAmount = $this->betAmount;

        $this->chargeable = $this->balanceAvailable >= $this->betAmount;
    }
}
