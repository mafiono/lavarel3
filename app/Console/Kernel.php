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
        \App\Console\Commands\BetResolverCommand::class,
        \App\Console\Commands\BonusCancellerCommand::class,
        \App\Console\Commands\SelfExcludedList::class,
        \App\Console\Commands\AffiliatesCsv::class,
        \App\Console\Commands\AffiliatesActivity::class,
        \App\Console\Commands\TestIdentityVerifier::class,
        \App\Console\Commands\BetCreatorCommand::class,
        \App\Console\Commands\TestEmail::class,
        \App\Console\Commands\EmailAgent::class,
        \App\Console\Commands\CreateMultiMarketsIds::class,
        \App\Console\Commands\TestMeoWallet::class,
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
            ->cron('5 * * * * *');

        $schedule->command('affiliates-activity')
            ->dailyAt('23:55');

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

        $schedule->command('email_agent')
            ->appendOutputTo($file)
//            ->everyMinute()
            ->dailyAt('00:40')
        ;
    }
}
