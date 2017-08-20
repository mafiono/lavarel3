<?php

namespace App\Providers;

use App\Bonus\Sports\BaseSportsBonus;
use Illuminate\Support\ServiceProvider;

class CasinoBonusServiceProvider extends ServiceProvider
{
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
        $this->app->singleton('casino.bonus', function () {
            return BaseSportsBonus::make();
        });
    }
}
