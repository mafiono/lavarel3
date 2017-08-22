<?php

namespace App\Bonus\Casino\Filters;

use App\User;
use App\UserTransaction;
use Illuminate\Database\Eloquent\Collection;

class TargetDepositMethods extends Filter
{
    protected $latestDeposit;

    public function __construct(User $user = null, UserTransaction $latestDeposit = null)
    {
        parent::__construct($user);

        $this->latestDeposit = $latestDeposit;
    }

    public function run()
    {
        if (is_null($this->latestDeposit)) {
            $this->data = new Collection();

            return;
        }

        $this->data = $this->data->filter(function ($bonus) {
           return !$bonus->depositMethods->where('deposit_method_id', $this->latestDeposit->origin)
               ->isEmpty();
        });
    }
}