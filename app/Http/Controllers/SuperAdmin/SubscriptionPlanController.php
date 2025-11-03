<?php
namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::latest()->paginate(10);
        return view('super-admin.subscription-plans.index', compact('plans'));
    }

    public function create()
    {
        return view('super-admin.subscription-plans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'trial_days' => 'required|integer|min:0',
            'billing_cycle' => 'required|in:monthly,yearly',
            'max_staff' => 'required|integer|min:1',
            'max_patients' => 'required|integer|min:1',
            'max_departments' => 'required|integer|min:1',
            'features.*' => 'boolean',
        ]);

        $features = [
            'emr' => $request->has('features.emr'),
            'lab' => $request->has('features.lab'),
            'pharmacy' => $request->has('features.pharmacy'),
            'billing' => $request->has('features.billing'),
            'appointments' => $request->has('features.appointments'),
            'reports' => $request->has('features.reports'),
            'ai' => $request->has('features.ai'),
            'mobile_app' => $request->has('features.mobile_app'),
            'sms' => $request->has('features.sms'),
            'api' => $request->has('features.api'),
        ];

        SubscriptionPlan::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'trial_days' => $request->trial_days,
            'billing_cycle' => $request->billing_cycle,
            'max_staff' => $request->max_staff,
            'max_patients' => $request->max_patients,
            'max_departments' => $request->max_departments,
            'features' => $features,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('super-admin.subscription-plans.index')
            ->with('success', 'Subscription plan created successfully!');
    }

    public function update(Request $request, SubscriptionPlan $plan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'trial_days' => 'required|integer|min:0',
            'billing_cycle' => 'required|in:monthly,yearly',
            'max_staff' => 'required|integer|min:1',
            'max_patients' => 'required|integer|min:1',
            'max_departments' => 'required|integer|min:1',
            'features.*' => 'boolean',
        ]);

        $features = [
            'emr' => $request->has('features.emr'),
            'lab' => $request->has('features.lab'),
            'pharmacy' => $request->has('features.pharmacy'),
            'billing' => $request->has('features.billing'),
            'appointments' => $request->has('features.appointments'),
            'reports' => $request->has('features.reports'),
            'ai' => $request->has('features.ai'),
            'mobile_app' => $request->has('features.mobile_app'),
            'sms' => $request->has('features.sms'),
            'api' => $request->has('features.api'),
        ];

        $plan->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'trial_days' => $request->trial_days,
            'billing_cycle' => $request->billing_cycle,
            'max_staff' => $request->max_staff,
            'max_patients' => $request->max_patients,
            'max_departments' => $request->max_departments,
            'features' => $features,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('super-admin.subscription-plans.index')
            ->with('success', 'Subscription plan updated successfully!');
    }
    public function edit(SubscriptionPlan $plan)
    {
        return view('super-admin.subscription-plans.edit', compact('plan'));
    }

    public function destroy(SubscriptionPlan $plan)
    {
        if ($plan->hospitals()->count() > 0) {
            return back()->withErrors('Cannot delete plan with active hospitals.');
        }

        $plan->delete();
        return back()->with('success', 'Subscription plan deleted successfully!');
    }
}