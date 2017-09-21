<?php

namespace App\Bonus\Casino\Filters;

class CasinoNoDeposit extends Filter
{
    public function run()
    {
        $this->data = $this->data->where('bonus_type_id', 'casino_no_deposit');

        $this->filter(new UserIsApproved($this->user))
            ->combine([
                new UsernameTargeted($this->user),
                new UserGroupsTargeted($this->user)
            ], 'id')
        ;
    }

}