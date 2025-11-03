<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\SubscriptionPlan;
use App\Models\HospitalSubscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HospitalRegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();
        return view('hospital-registration.register', compact('plans'));
    }

    public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|alpha_dash|unique:hospitals,slug',
        'email' => 'required|email|unique:hospitals,email',
        'phone' => 'nullable|string',
        'address' => 'nullable|string',
        'city' => 'nullable|string',
        'state' => 'nullable|string',
        'admin_email' => 'required|email|unique:users,email',
        'admin_password' => 'required|min:8',
        'subscription_plan_id' => 'required|exists:subscription_plans,id',
        'payment_method' => 'required|in:paystack,flutterwave',
    ]);

    try {
        DB::transaction(function () use ($request) {
            // Get selected plan
            $plan = SubscriptionPlan::findOrFail($request->subscription_plan_id);

            // Calculate subscription end date
            $startsAt = now();
            $endsAt = $plan->billing_cycle === 'yearly' 
                ? $startsAt->copy()->addYear()
                : $startsAt->copy()->addMonth();

            // Create hospital
            $hospital = Hospital::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country ?? 'Nigeria',
                'is_active' => true,
                'subscription_plan_id' => $plan->id,
                'is_trial_active' => $plan->trial_days > 0,
                'trial_ends_at' => $plan->trial_days > 0 ? now()->addDays($plan->trial_days) : null,
                'subscription_ends_at' => $plan->trial_days > 0 ? null : $endsAt,
            ]);

            // Create admin user
            User::create([
                'name' => $request->name . ' Admin',
                'email' => $request->admin_email,
                'password' => Hash::make($request->admin_password),
                'role' => User::ROLE_ADMIN,
                'hospital_id' => $hospital->id,
                'phone' => $request->phone,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // Create subscription record
            if ($plan->trial_days > 0) {
                // Free trial - no payment needed
                HospitalSubscription::create([
                    'hospital_id' => $hospital->id,
                    'plan_id' => $plan->id,
                    'payment_gateway' => null,
                    'payment_reference' => null,
                    'amount_paid' => 0,
                    'starts_at' => $startsAt,
                    'ends_at' => $endsAt,
                    'is_active' => true,
                ]);
            } else {
                // Paid subscription - process payment
                $amount = $plan->price * 100; // Convert to kobo
                $reference = uniqid('sub_');

                // Create payment record
                $payment = \App\Models\Payment::create([
                    'hospital_id' => $hospital->id,
                    'user_id' => null, // No user yet
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
                        'email' => $request->admin_email,
                        'amount' => $amount,
                        'reference' => $reference,
                        'callback_url' => route('hospital.registration.callback'),
                        'metadata' => $payment->metadata,
                    ]);

                    session(['registration_payment_reference' => $reference]);
                    return redirect($result['authorization_url']);
                } catch (\Exception $e) {
                    \Log::error('Subscription payment failed', ['error' => $e->getMessage()]);
                    return back()->withErrors('Payment initialization failed. Please try again.');
                }
            }
        });

        return redirect()->route('hospital.registration.success')
            ->with('success', 'Hospital registered successfully!');
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Registration failed: ' . $e->getMessage())
            ->withInput();
    }
}

    public function callback(Request $request)
    {
        $reference = session('registration_payment_reference');
        $payment = \App\Models\Payment::where('reference', $reference)->first();

        if (!$payment) {
            return redirect('/')->withErrors('Invalid payment reference.');
        }

        // In a real app, verify payment status via webhook or API call
        // For now, assume successful payment
        $payment->update(['status' => 'successful']);

        $hospital = Hospital::findOrFail($payment->hospital_id);
        $plan = SubscriptionPlan::findOrFail($payment->metadata['plan_id']);

        // Create subscription record
        HospitalSubscription::create([
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

        return redirect()->route('hospital.registration.success')
            ->with('success', 'Hospital registered successfully!');
    }
}