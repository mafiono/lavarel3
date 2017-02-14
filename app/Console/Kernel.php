<?php

namespace App\Console;

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
        \App\Console\Commands\BetResolverCommand::class,
        \App\Console\Commands\BonusCancellerCommand::class,
        \App\Console\Commands\SelfExcludedList::class,
        \App\Console\Commands\AffiliatesCsv::class,
        \App\Console\Commands\TestIdentityVerifier::class,
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

        $schedule->command('affiliates-csv')
            ->dailyAt('23:59');
        
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

        $schedule->command('cancel-bonus')
            ->appendOutputTo(env('BONUS_CANCELLER_LOG', 'storage/logs/bonus_canceller.log'))
            ->everyTenMinutes();

        $schedule->command('self-excluded-list')
            ->appendOutputTo($file)
//            ->everyMinute()
            ->dailyAt('02:20')
        ;
    }
}
