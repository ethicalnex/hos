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
            'max_departments' => 'required|integer|min:1',
            'max_patients' => 'required|integer|min:1',
            'can_create_staff' => 'boolean',
            'can_create_departments' => 'boolean',
            'can_create_patients' => 'boolean',
        ]);

        // ✅ CREATE WITH ALL FILLABLE FIELDS
        $plan = new SubscriptionPlan();
        $plan->name = $request->name;
        $plan->description = $request->description;
        $plan->price = $request->price;
        $plan->trial_days = $request->trial_days;
        $plan->billing_cycle = $request->billing_cycle;
        $plan->max_staff = $request->max_staff;
        $plan->max_departments = $request->max_departments;
        $plan->max_patients = $request->max_patients;
        $plan->can_create_staff = $request->has('can_create_staff');
        $plan->can_create_departments = $request->has('can_create_departments');
        $plan->can_create_patients = $request->has('can_create_patients');
        $plan->is_active = $request->has('is_active');
        $plan->save();

        // ✅ REDIRECT TO INDEX WITH SUCCESS MESSAGE
        return redirect()->route('super-admin.subscription-plans.index')
            ->with('success', 'Subscription plan created successfully!');
    }

    public function edit(SubscriptionPlan $plan)
    {
        return view('super-admin.subscription-plans.edit', compact('plan'));
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
            'max_departments' => 'required|integer|min:1',
            'max_patients' => 'required|integer|min:1',
            'can_create_staff' => 'boolean',
            'can_create_departments' => 'boolean',
            'can_create_patients' => 'boolean',
        ]);

        $plan->update($request->all());

        return redirect()->route('super-admin.subscription-plans.index')
            ->with('success', 'Subscription plan updated successfully!');
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