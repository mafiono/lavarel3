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

    public static function getBalanceSplit()
    {
        return 0.70;
    }

    public static function getBonusSplit()
    {
        return 0.30;
    }

    public static function getBetLowerLimit()
    {
        return 2;
    }

    public static function getBetUpperLimit()
    {
        return 100;
    }

    public static function maxFirstDepositBonus()
    {
        return 100;
    }
}