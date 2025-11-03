<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-appointment-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $appointments = Appointment::where('status', 'confirmed')
        ->whereDate('scheduled_time', now()->addDay())
        ->get();

    foreach ($appointments as $appt) {
        \Mail::to($appt->patient->email)->send(new \App\Mail\AppointmentReminder($appt));
    }
    }
}
