<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\SubscriptionPlan;
use App\Models\HospitalSubscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function plan(Request $request)
    {
        $hospital = $request->user()->hospital;

        $plan = $hospital->subscriptionPlan;

        return response()->json([
            'plan' => $plan ? [
                'name' => $plan->name,
                'description' => $plan->description,
                'price' => $plan->price,
                'features' => $plan->features,
                'billing_cycle' => $plan->billing_cycle,
            ] : null,
            'is_trial_active' => $hospital->isTrialActive(),
            'trial_ends_at' => $hospital->trial_ends_at,
            'subscription_ends_at' => $hospital->subscription_ends_at,
        ]);
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'payment_gateway' => 'required|in:paystack,flutterwave',
        ]);

        $hospital = $request->user()->hospital;
        $plan = SubscriptionPlan::findOrFail($request->plan_id);

        // Create subscription record
        $subscription = HospitalSubscription::create([
            'hospital_id' => $hospital->id,
            'plan_id' => $plan->id,
            'is_active' => true,
            'starts_at' => now(),
            'ends_at' => $plan->billing_cycle === 'yearly'
                ? now()->copy()->addYear()
                : now()->copy()->addMonth(),
            'payment_gateway' => $request->payment_gateway,
        ]);

        // Update hospital
        $hospital->update([
            'subscription_plan_id' => $plan->id,
            'subscription_ends_at' => $subscription->ends_at,
            'is_trial_active' => false,
            'trial_ends_at' => null,
        ]);

        return response()->json([
            'message' => 'Subscription activated successfully.',
            'subscription' => $subscription,
        ], 201);
    }

    public function renew(Request $request)
    {
        $hospital = $request->user()->hospital;

        if (!$hospital->hasActiveSubscription()) {
            return response()->json(['error' => 'No active subscription to renew.'], 400);
        }

        $plan = $hospital->subscriptionPlan;

        $subscription = HospitalSubscription::create([
            'hospital_id' => $hospital->id,
            'plan_id' => $plan->id,
            'is_active' => true,
            'starts_at' => now(),
            'ends_at' => $plan->billing_cycle === 'yearly'
                ? now()->copy()->addYear()
                : now()->copy()->addMonth(),
            'payment_gateway' => $request->payment_gateway ?? 'paystack',
        ]);

        $hospital->update([
            'subscription_ends_at' => $subscription->ends_at,
        ]);

        return response()->json([
            'message' => 'Subscription renewed successfully.',
            'subscription' => $subscription,
        ], 200);
    }
}