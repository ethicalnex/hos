<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Hospital;
use Carbon\Carbon;

class CheckTrialExpirations extends Command
{
    protected $signature = 'subscription:check-trial-expirations';
    protected $description = 'Check for hospitals with expiring trials';

    public function handle()
    {
        $today = Carbon::today();
        
        // Find hospitals with trial ending today
        $expiringToday = Hospital::where('is_trial_active', true)
            ->whereDate('trial_ends_at', $today)
            ->get();

        foreach ($expiringToday as $hospital) {
            // Send notification
            $hospital->user->notify(new \App\Notifications\TrialExpiring($hospital));
            
            // Log the action
            \App\Models\SubscriptionLog::create([
                'hospital_id' => $hospital->id,
                'plan_id' => $hospital->subscription_plan_id,
                'action' => 'trial_ended',
                'notes' => 'Free trial expired',
            ]);
            
            // Deactivate trial
            $hospital->update([
                'is_trial_active' => false,
                'trial_ends_at' => null,
            ]);
            
            $this->info("Trial expired for hospital: {$hospital->name}");
        }

        $this->info('Trial expiration check completed.');
    }
}