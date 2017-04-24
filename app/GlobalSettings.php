<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GlobalSettings extends Model
{
    protected $table = 'global_settings';

    static $highBetAmountCompetitions = [
        '61', //Liga NOS
        '53', //'Liga de Fútbol Profesional
    ];

    static $mediumBetAmountCompetitions = [
        '40', //Bundesliga
    ];

    public static function getTax()
    {
        return 0.00;
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
}