<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GlobalSettings extends Model
{
    protected $table = 'global_settings';

    public static function getTax()
    {
        return 0.00;
    }

    public static function balanceSplit()
    {
        return 0.70;
    }

    public static function bonusSplit()
    {
        return 0.30;
    }

}