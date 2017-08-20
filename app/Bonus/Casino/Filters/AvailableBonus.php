<?php

namespace App\Bonus\Casino\Filters;

use App\Bonus;
use App\User;
use UserBonus;

class AvailableBonus extends Filter
{
    public function __construct(User $user)
    {
        parent::__construct();

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
            ->exists();
    }

    protected function availableBonuses()
    {
        return Bonus::currents()
            ->availableBetweenNow()
            ->unUsed($this->user)
            ->get();
    }
}