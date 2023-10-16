<?php

namespace App\Console;

use App\Jobs\FetchBlackHawk;
use App\Jobs\GetOrderStatus;
use App\Jobs\ProcessOrderQueue;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(new FetchBlackHawk())->dailyAt('04:00');
        $schedule->job(new ProcessOrderQueue())->everyMinute();
        $schedule->job(new GetOrderStatus())->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
