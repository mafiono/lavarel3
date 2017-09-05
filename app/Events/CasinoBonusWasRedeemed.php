<?php

namespace App\Events;

use App\UserBonus;
use Illuminate\Queue\SerializesModels;

class CasinoBonusWasRedeemed extends Event
{
    use SerializesModels;

    public $userBonus;

    public function __construct(UserBonus $userBonus)
    {
        $this->userBonus = $userBonus;
    }
}