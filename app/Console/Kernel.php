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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $filePath = 'C:\sc\ra.txt';

        $schedule->command('command:ignore_feedback')->weekly();
        $schedule->command('command:ignore_report')->dailyAt('13:00');
        $schedule->command('command:order')->dailyAt('13:00');

        // $schedule->command('reports:ignore_urgent')->hourly();
        // $schedule->command('reports:ignore_nonurgent')->everySixHours();
        // $schedule->command('delete:old_pictures')->twiceMonthly(1, 16, '24');
        // $schedule->command('delete:old_pictures')->everyMinute()->appendOutputTo($filePath);

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
