<?php

namespace App\Bonus\Casino\Filters;

use App\Bonus;

class UserGroupsTargeted extends Filter
{
    public function run()
    {
        $this->data = $this->data->filter(function ($bonus) {
            return !($this->targetsUserGroup($bonus, 'rating_risk')
                || $this->targetsUserGroup($bonus, 'rating_group')
                || $this->targetsUserGroup($bonus, 'rating_type')
                || $this->targetsUserGroup($bonus, 'rating_class'));
        });
    }

    protected function targetsUserGroup(Bonus $bonus, $group)
    {
        return !$bonus->targets->where('target_id', '=', $this->user->{$group})
            ->empty();
    }
}