<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GlobalSettings extends Model
{
    protected $table = 'global_settings';

    public static function getTax()
    {
        return 0.08;
    }

    public static function getBetLowerLimit()
    {
        return 1;
    }

    public static function getBetUpperLimit($group = 'g3')
    {
        if ($group === 'g1')
            return 1500;

        if ($group === 'g2')
            return 1000;

        return 500;
    }

    public static function getPrizeUpperLimit()
    {
        return 50000;
    }

    public static function maxFirstDepositBonus()
    {
        return 100;
    }

    public static function getFirstDepositBalanceSplit()
    {
        return 0.90;
    }

    public static function getFirstDepositBonusSplit()
    {
        return 0.10;
    }

    public static function getFirstDepositEventMinOdds()
    {
        return 1.30;
    }
}