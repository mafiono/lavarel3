<?php

namespace App\Bets;


class CollectorReceipt
{
    private $receipt;

    /**
     * CollectorReceipt constructor.
     * @param array $receipt
     */
    public function __construct(array $receipt)
    {
        $this->receipt = $receipt;
    }

    /**
     * @return string
     */
    public function getOwnerId()
    {
        return (string) $this->receipt['owner_id'];
    }

    /**
     * @return float
     */
    public function getInitialBalance()
    {
        return (float) $this->receipt['initial_balance'];
    }

    /**
     * @return float
     */
    public function getFinalBalance()
    {
        return (float) $this->receipt['final_balance'];
    }

    /**
     * @return float
     */
    public function getInitialBonus()
    {
        return (float) $this->receipt['initial_bonus'];
    }

    /**
     * @return float
     */
    public function getFinalBonus()
    {
        return (float) $this->receipt['final_bonus'];
    }

    /**
     * @return float
     */
    public function getAmountBalance() {
        return (float) $this->receipt['amount_balance'];
    }

    /**
     * @return float
     */
    public function getAmountBonus()
    {
        return (float) $this->receipt['amount_bonus'];
    }

    public function getOperation() {
        return (string) $this->receipt['operation'];
    }

}