<?php

namespace App\Providers;

use App\Bonus\BaseSportsBonus;
use Illuminate\Support\ServiceProvider;

class SportsBonusServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton('sports.bonus', function () {
            return BaseSportsBonus::make();
        });
    }


}
