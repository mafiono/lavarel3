<?php

namespace App\Providers;

use App\Bonus\SportsBonus;
use Illuminate\Support\ServiceProvider;

class SportsBonusServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('sports.bonus', function () {
            return SportsBonus::make();
        });
    }

}
