<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\SendAppointmentReminders::class,
        \App\Console\Commands\CreateSuperAdmin::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Send appointment reminders at 8 AM daily
        $schedule->command('appointments:reminders')->dailyAt('08:00');
        
        // Mark no-show appointments (2 hours after scheduled time)
        $schedule->command('appointments:mark-no-show')->hourly();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }

    protected function schedule(Schedule $schedule)
    {
        // Check for trial expirations daily
        $schedule->command('subscription:check-trial-expirations')->daily();

        // Send subscription reminders 3 days before renewal
        $schedule->command('subscription:send-reminders')->daily();
    }
}