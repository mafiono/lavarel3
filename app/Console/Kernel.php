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
        $dir = dirname(__FILE__);
        $file = $dir.'\..\..\storage\logs\tasks.log';
        $file = $this->normalizePath(str_replace('\\', '/', $file));
        $file = str_replace('/', '\\', $file);

        $schedule->command('inspire')
//        $schedule->call(function () use($file){
//            print_r($file);
//            print_r('Testing At:' . Carbon::now());
//        })
            ->appendOutputTo($file)
            ->everyMinute();

        $schedule->command('inspire')
                 ->hourly();
    }

    /**
     * Normalize path
     *
     * @param   string  $path
     * @param   string  $separator
     * @return  string  normalized path
     */
    public function normalizePath($path, $separator = '\\/')
    {
        // Remove any kind of funky unicode whitespace
        $normalized = preg_replace('#\p{C}+|^\./#u', '', $path);

        // Path remove self referring paths ("/./").
        $normalized = preg_replace('#/\.(?=/)|^\./|\./$#', '', $normalized);

        // Regex for resolving relative paths
        $regex = '#\/*[^/\.]+/\.\.#Uu';

        while (preg_match($regex, $normalized)) {
            $normalized = preg_replace($regex, '', $normalized);
        }

        if (preg_match('#/\.{2}|\.{2}/#', $normalized)) {
            throw new LogicException('Path is outside of the defined root, path: [' . $path . '], resolved: [' . $normalized . ']');
        }

        return trim($normalized, $separator);
    }
}
