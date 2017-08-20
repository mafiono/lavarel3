<?php

namespace App\Bonus\Casino\Filters;

class UserTargeted extends Filter
{

    public function run()
    {
        $this->data = $this->data->filter(function ($bonus) {
           return $bonus->usernameTargets->where('username', '=', $this->user->username)
               ->isEmpty();
        });
    }
}