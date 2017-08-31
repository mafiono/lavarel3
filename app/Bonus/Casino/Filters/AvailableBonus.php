<?php

namespace App\Bonus\Casino\Filters;

use App\Bonus;
use App\User;
use App\UserBonus;

class AvailableBonus extends Filter
{
    public function __construct(User $user)
    {
        parent::__construct($user);

        if (!$this->hasActive()) {
            $this->data = $this->availableBonuses();
        }
    }
    public function run()
    {
        return $this;
    }

    protected function hasActive()
    {
        return UserBonus::activeFromUser($this->user->id)
            ->origin('casino')
            ->exists();
    }

    protected function availableBonuses()
    {
        return Bonus::currents()
            ->origin('casino')
            ->availableBetweenNow()
            ->unUsed($this->user)
            ->get();
    }
}