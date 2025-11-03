<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Hospital;
use Carbon\Carbon;

class SendSubscriptionReminders extends Command
{
    protected $signature = 'subscription:send-reminders';
    protected $description = 'Send reminders for upcoming subscription renewals';

    public function handle()
    {
        $today = Carbon::today();
        $reminderDate = $today->copy()->addDays(3); // 3 days before renewal
        
        // Find hospitals with renewal in 3 days
        $upcomingRenewals = \App\Models\SubscriptionRenewal::where('renewal_date', $reminderDate)
            ->where('is_paid', false)
            ->with('hospital', 'plan')
            ->get();

        foreach ($upcomingRenewals as $renewal) {
            // Send notification
            $renewal->hospital->user->notify(new \App\Notifications\SubscriptionReminder($renewal));
            
            $this->info("Reminder sent for hospital: {$renewal->hospital->name}");
        }

        $this->info('Subscription reminders sent.');
    }
}