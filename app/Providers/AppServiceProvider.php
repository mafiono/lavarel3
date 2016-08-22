<?php

namespace App\Providers;

use App;
use App\Bonus\BaseSportsBonus;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        App::singleton('sports.bonus', function () {
            return BaseSportsBonus::make();
        });
    }
}
