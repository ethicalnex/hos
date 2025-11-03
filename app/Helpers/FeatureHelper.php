<?php

namespace App\Helpers;

class FeatureHelper
{
    public static function check($feature)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $hospital = auth()->user()->hospital;

        // If no hospital assigned, deny access
        if (!$hospital) {
            abort(403, 'No hospital assigned to user');
        }

        // Check if feature is available in current plan
        if (!$hospital->subscriptionPlan || !$hospital->subscriptionPlan->hasFeature($feature)) {
            return redirect()->route('hospital.subscription.index')
                ->with('upgrade_required', "This feature ($feature) requires a higher plan. Please upgrade your subscription.");
        }

        // Check if trial has expired
        if ($hospital->isTrialActive() && $hospital->trial_ends_at->isPast()) {
            return redirect()->route('hospital.subscription.index')
                ->with('trial_expired', 'Your free trial has expired. Please upgrade to continue using premium features.');
        }

        return null;
    }
}