<?php
namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use App\Models\SubscriptionRenewal;

class SubscriptionController extends Controller
{
    public function index()
    {
        $hospital = auth()->user()->hospital;
        
        // Get current subscription status
        $subscriptionStatus = [
            'hasActiveSubscription' => $hospital->hasActiveSubscription(),
            'isTrialActive' => $hospital->isTrialActive(),
            'trialEndsAt' => $hospital->trial_ends_at,
            'subscriptionEndsAt' => $hospital->subscription_ends_at,
        ];

        // Get upcoming renewals
        $upcomingRenewals = SubscriptionRenewal::where('hospital_id', $hospital->id)
            ->where('renewal_date', '>=', now())
            ->where('is_paid', false)
            ->orderBy('renewal_date')
            ->get();

        return view('hospital.subscription.index', compact('subscriptionStatus', 'upcomingRenewals'));
    }

    public function renew($renewalId)
    {
        $renewal = SubscriptionRenewal::findOrFail($renewalId);
        
        if ($renewal->hospital_id !== auth()->user()->hospital_id) {
            abort(403);
        }

        $plan = SubscriptionPlan::findOrFail($renewal->plan_id);

        // Process payment for renewal
        $amount = $plan->price * 100; // Convert to kobo
        $reference = uniqid('renew_');

        // Create payment record
        $payment = \App\Models\Payment::create([
            'hospital_id' => $renewal->hospital_id,
            'user_id' => auth()->id(),
            'payment_type' => 'subscription_renewal',
            'payment_gateway' => $renewal->payment_gateway,
            'reference' => $reference,
            'amount' => $amount,
            'status' => 'pending',
            'metadata' => [
                'renewal_id' => $renewal->id,
                'plan_id' => $plan->id,
                'hospital_id' => $renewal->hospital_id,
            ],
        ]);

        try {
            $service = PaymentGatewayFactory::make($renewal->payment_gateway, $renewal->hospital_id);
            $result = $service->initialize([
                'email' => auth()->user()->email,
                'amount' => $amount,
                'reference' => $reference,
                'callback_url' => route('hospital.subscription.renew.callback', $renewal),
                'metadata' => $payment->metadata,
            ]);

            session(['renewal_payment_reference' => $reference]);
            return redirect($result['authorization_url']);
        } catch (\Exception $e) {
            \Log::error('Subscription renewal failed', ['error' => $e->getMessage()]);
            return back()->withErrors('Payment initialization failed. Please try again.');
        }
    }

    public function renewCallback(Request $request, SubscriptionRenewal $renewal)
    {
        $reference = session('renewal_payment_reference');
        $payment = \App\Models\Payment::where('reference', $reference)->first();

        if (!$payment) {
            return redirect()->route('hospital.subscription.index')->withErrors('Invalid payment reference.');
        }

        // In a real app, verify payment status via webhook or API call
        // For now, assume successful payment
        $payment->update(['status' => 'successful']);

        // Update hospital subscription
        $hospital = Hospital::findOrFail($renewal->hospital_id);
        $plan = SubscriptionPlan::findOrFail($renewal->plan_id);

        // Extend subscription end date
        $endsAt = $plan->billing_cycle === 'yearly' 
            ? now()->copy()->addYear()
            : now()->copy()->addMonth();

        $hospital->update([
            'subscription_ends_at' => $endsAt,
            'is_trial_active' => false,
            'trial_ends_at' => null,
        ]);

        // Mark renewal as paid
        $renewal->update(['is_paid' => true]);

        // Log the action
        SubscriptionLog::create([
            'hospital_id' => $hospital->id,
            'plan_id' => $plan->id,
            'action' => 'subscription_renewed',
            'notes' => "Renewed from {$plan->name} plan",
        ]);

        return redirect()->route('hospital.subscription.index')
            ->with('success', 'Subscription renewed successfully!');
    }

    public function cancel()
    {
        $hospital = auth()->user()->hospital;
        
        // Cancel active subscription
        $hospital->update([
            'subscription_ends_at' => now(),
            'is_trial_active' => false,
            'trial_ends_at' => null,
        ]);

        // Log the action
        SubscriptionLog::create([
            'hospital_id' => $hospital->id,
            'plan_id' => $hospital->subscription_plan_id,
            'action' => 'subscription_cancelled',
            'notes' => 'Subscription cancelled by user',
        ]);

        return redirect()->route('hospital.subscription.index')
            ->with('success', 'Subscription cancelled successfully!');
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'payment_method' => 'required|in:paystack,flutterwave',
        ]);

        $hospital = auth()->user()->hospital;
        $plan = SubscriptionPlan::findOrFail($request->plan_id);

        // Handle trial
        if ($plan->trial_days > 0) {
            $hospital->update([
                'subscription_plan_id' => $plan->id,
                'is_trial_active' => true,
                'trial_ends_at' => now()->addDays($plan->trial_days),
                'subscription_ends_at' => null,
            ]);

            return redirect()->route('hospital.subscription.index')
                ->with('success', 'Trial activated! You have ' . $plan->trial_days . ' days free.');
        }

        // Process payment for immediate subscription
        $amount = $plan->price * 100; // Convert to kobo
        $reference = uniqid('sub_');

        // Create payment record
        $payment = \App\Models\Payment::create([
            'hospital_id' => $hospital->id,
            'user_id' => auth()->id(),
            'payment_type' => 'subscription',
            'payment_gateway' => $request->payment_method,
            'reference' => $reference,
            'amount' => $amount,
            'status' => 'pending',
            'metadata' => [
                'plan_id' => $plan->id,
                'hospital_id' => $hospital->id,
            ],
        ]);

        // Initialize payment
        try {
            $service = PaymentGatewayFactory::make($request->payment_method, $hospital->id);
            $result = $service->initialize([
                'email' => auth()->user()->email,
                'amount' => $amount,
                'reference' => $reference,
                'callback_url' => route('hospital.subscription.callback'),
                'metadata' => $payment->metadata,
            ]);

            session(['subscription_payment_reference' => $reference]);
            return redirect($result['authorization_url']);
        } catch (\Exception $e) {
            \Log::error('Subscription payment failed', ['error' => $e->getMessage()]);
            return back()->withErrors('Payment initialization failed. Please try again.');
        }
    }

    public function callback(Request $request)
    {
        $reference = session('subscription_payment_reference');
        $payment = \App\Models\Payment::where('reference', $reference)->first();

        if (!$payment) {
            return redirect()->route('hospital.subscription.index')->withErrors('Invalid payment reference.');
        }

        // In a real app, verify payment status via webhook or API call
        // For now, assume successful payment
        $payment->update(['status' => 'successful']);

        $hospital = Hospital::findOrFail($payment->hospital_id);
        $plan = SubscriptionPlan::findOrFail($payment->metadata['plan_id']);

        // Create subscription record
        \App\Models\HospitalSubscription::create([
            'hospital_id' => $hospital->id,
            'plan_id' => $plan->id,
            'payment_gateway' => $payment->payment_gateway,
            'payment_reference' => $reference,
            'amount_paid' => $plan->price,
            'starts_at' => now(),
            'ends_at' => $plan->billing_cycle === 'yearly' 
                ? now()->copy()->addYear()
                : now()->copy()->addMonth(),
            'is_active' => true,
        ]);

        // Update hospital subscription
        $hospital->update([
            'subscription_ends_at' => $plan->billing_cycle === 'yearly' 
                ? now()->copy()->addYear()
                : now()->copy()->addMonth(),
            'is_trial_active' => false,
            'trial_ends_at' => null,
        ]);

        return redirect()->route('hospital.subscription.index')
            ->with('success', 'Subscription activated successfully!');
    }
}