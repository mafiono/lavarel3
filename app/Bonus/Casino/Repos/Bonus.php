<?php

namespace App\Bonus\Casino\Repos;


use UserBonus;

class Bonus
{
    public function getActive($userId)
    {
        UserBonus::activeFromUser($userId, ['bonus'])->first();
    }

    public function getAvailable($userId)
    {
        //casino origin
        //is current
        //between now

        //filter by unused head id

        //specific bonus logic
    }

}