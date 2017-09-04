<?php

namespace App\Bonus\Casino\Filters;

use App\User;
use App\UserTransaction;

class CasinoDeposit extends Filter
{
    protected $latestDeposit;

    public function __construct(User $user = null)
    {
        parent::__construct($user);

        $this->latestDeposit = $this->latestDeposit();
    }

    public function run()
    {
        $this->data = $this->data->where('bonus_type_id', 'casino_deposit');

        $this->filter(new AboveMinDeposit($this->user, $this->latestDeposit))
            ->filter(new TargetDepositMethods($this->user, $this->latestDeposit))
            ->filter(new NoBonusSinceLastDeposit($this->user, $this->latestDeposit))
            ->combine([
                new UsernameTargeted($this->user),
                new UserGroupsTargeted($this->user)
            ], 'id')
        ;
    }

    protected function latestDeposit()
    {
        return UserTransaction::latestUserDeposit($this->user->id)
            ->first();
    }
}