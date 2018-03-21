<?php

namespace App\Providers;

use App\Bonus\Sports\BaseSportsBonus;
use Illuminate\Support\ServiceProvider;

class SportsBonusServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('sports.bonus', function () {
            return BaseSportsBonus::make();
        });
    }
}
