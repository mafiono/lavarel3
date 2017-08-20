<?php

namespace App\Bonus\Casino\Filters;

use App\User;
use App\UserBonus;
use App\UserTransaction;
use Illuminate\Database\Eloquent\Collection;

class NoBonusSinceLastDeposit extends Filter
{
    protected $latestDeposit;

    public function __construct(User $user = null, UserTransaction $latestDeposit)
    {
        parent::__construct($user);

        $this->latestDeposit = $latestDeposit;
    }

    public function run()
    {
        if ($this->usedBonusSinceLastDeposit())
            $this->data = new Collection();
    }

    protected function usedBonusSinceLastDeposit()
    {
        return UserBonus::createdSinceFromUser($this->latestDeposit->created_at, $this->user->id)
            ->exists();
    }
}