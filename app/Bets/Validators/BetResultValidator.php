<?php

namespace App\Bets\Validators;

use \Exception;

class BetResultBetValidator extends BetValidator
{

    public function validate()
    {
        $this->checkBetData();
        $this->statusValidation();

        return true;
    }

    protected function statusValidation()
    {
        if ($this->bet->status !== 'waiting_result')
            throw new Exception('Bet is already resolved');
    }

}