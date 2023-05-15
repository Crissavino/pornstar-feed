<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule
            ->command('feed:download')
            ->everyThreeHours()
            ->withoutOverlapping()
            ->runInBackground();

        // run php artisan queue:retry all failed jobs every 5 minutes
        $schedule
            ->command('queue:retry all')
            ->everyFiveMinutes()
            ->withoutOverlapping()
            ->runInBackground();

        // php artisan queue:flush every 1 hour
        $schedule
            ->command('queue:flush')
            ->hourly()
            ->withoutOverlapping()
            ->runInBackground();

        // php artisan queue:clear every 2 hour
        $schedule
            ->command('queue:clear')
            ->hourly()
            ->withoutOverlapping()
            ->runInBackground();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
