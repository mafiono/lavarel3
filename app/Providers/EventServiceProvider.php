<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\BetWasWagered' => [
            'App\Listeners\BetWasWagered\HandleBonus',
        ],
        'App\Events\BetWasResulted' => [
            'App\Listeners\BetWasResulted\HandleBonus',
        ],
        'App\Events\WithdrawalWasRequested' => [
            'App\Listeners\WithdrawalWasRequested\HandleBonus',
        ]
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
