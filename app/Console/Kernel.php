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
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\CheckBalance::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $file = 'log_file.log';
        $schedule->command('check-balance')
            ->before(function() use ($file){
                if (file_exists($file)){
                    unlink($file);
                }
            })
            ->appendOutputTo('log_file.log')
            ->everyMinute()
            // ->dailyAt('03:00')
            ->emailOutputTo(['jmiguelcouto@gmail.com']);

        $schedule->command('inspire')
                 ->hourly();
    }
}
