<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\CheckBalance::class,
        \App\Console\Commands\BetResolverJob::class,
        \App\Console\Commands\SelfExcludedList::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $file = 'storage/logs/log_file.log';
        $schedule->command('check-balance')
            ->before(function() use ($file){
                if (file_exists($file)){
                    unlink($file);
                }
            })
            ->appendOutputTo($file)
            ->dailyAt('03:00')
            ->emailOutputTo([env('TEST_MAIL')]);

        $schedule->command('resolve-bets')
            ->appendOutputTo(env('BET_RESOLVER_LOG', 'storage/logs/bet_resolver.log'))
            ->everyFiveMinutes();

        $schedule->command('self-excluded-list')
            ->appendOutputTo($file)
//            ->everyMinute()
            ->dailyAt('02:20')
        ;
    }
}
