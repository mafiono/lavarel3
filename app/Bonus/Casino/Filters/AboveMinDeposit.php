<?php

namespace App\Bonus\Casino\Filters;


use App\User;
use App\UserTransaction;

class aboveMinDeposit extends Filter
{
    protected $latestDeposit;

    public function __construct(User $user = null, UserTransaction $latestDeposit)
    {
        parent::__construct($user);

        $this->latestDeposit = $latestDeposit;
    }

    public function run()
    {
        $this->data = $this->data->filter(function($bonus) {
           return $bonus->min_deposit < $this->latestDeposit->debit;
        });
    }
}