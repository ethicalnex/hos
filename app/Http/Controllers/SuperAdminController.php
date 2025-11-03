<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Hospital;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    public function index()
    {
        // ✅ CONSISTENT stats array with correct keys
        $stats = [
            'hospitals' => Hospital::count(),
            'patients' => User::where('role', 'patient')->count(),
            'appointments' => Appointment::where('status', 'scheduled')->count(),
            'revenue' => Payment::where('status', 'successful')->sum('amount') / 100,
        ];

        $recentHospitals = Hospital::latest()->take(5)->get();

        // Chart data
        $months = [];
        $hospitalCounts = [];
        $startDate = now()->subMonths(5);
        
        for ($i = 0; $i <= 5; $i++) {
            $date = $startDate->copy()->addMonths($i);
            $months[] = $date->format('M Y');
            $hospitalCounts[] = Hospital::whereBetween('created_at', [
                $date->copy()->startOfMonth(),
                $date->copy()->endOfMonth()
            ])->count();
        }

        // Role distribution
        $roles = ['super_admin', 'admin', 'doctor', 'nurse', 'lab_technician', 'pharmacist', 'receptionist', 'patient'];
        $roleLabels = ['Super Admin', 'Hospital Admin', 'Doctor', 'Nurse', 'Lab Tech', 'Pharmacist', 'Receptionist', 'Patient'];
        $roleCounts = [];
        
        foreach ($roles as $role) {
            $roleCounts[] = User::where('role', $role)->count();
        }

        return view('super-admin.dashboard', [
            'stats' => $stats,
            'recentHospitals' => $recentHospitals,
            'chartData' => ['months' => $months, 'hospitalCounts' => $hospitalCounts],
            'roleData' => ['labels' => $roleLabels, 'counts' => $roleCounts]
        ]);
    }

    // Keep your other methods (hospitals, createHospital, etc.)
    public function hospitals()
    {
        $hospitals = Hospital::withCount(['users' => function($query) {
            $query->where('role', '!=', User::ROLE_PATIENT);
        }])->latest()->paginate(10);

        return view('super-admin.hospitals.index', compact('hospitals'));
    }

    public function createHospital()
    {
        return view('super-admin.hospitals.create');
    }

    public function storeHospital(Request $request)
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
        ]);

        try {
            DB::transaction(function () use ($request) {
                // ✅ GET DEFAULT PLAN
                $defaultPlan = \App\Models\SubscriptionPlan::where('is_active', true)
                    ->orderBy('price', 'asc')
                    ->first();

                if (!$defaultPlan) {
                    // Handle error or create default
                    throw new \Exception('No active subscription plans available');
                }

                $isTrialActive = $defaultPlan->trial_days > 0;
                $trialEndsAt = $isTrialActive ? now()->addDays($defaultPlan->trial_days) : null;

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
                    'subscription_plan_id' => $defaultPlan->id, // ✅ AUTO-ASSIGN
                    'is_trial_active' => $isTrialActive,
                    'trial_ends_at' => $trialEndsAt,
                    'subscription_ends_at' => null,
                ]);

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
            });

            return redirect()->route('super-admin.hospitals')
                ->with('success', 'Hospital created successfully with default subscription plan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create hospital: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function editHospital(Hospital $hospital)
    {
        return view('super-admin.hospitals.edit', compact('hospital'));
    }

    public function updateHospital(Request $request, Hospital $hospital)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|alpha_dash|unique:hospitals,slug,' . $hospital->id,
            'email' => 'required|email|unique:hospitals,email,' . $hospital->id,
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $hospital->update($request->all());
        return redirect()->route('super-admin.hospitals')
            ->with('success', 'Hospital updated successfully!');
    }

    public function toggleHospital(Hospital $hospital)
    {
        $hospital->update(['is_active' => !$hospital->is_active]);
        $status = $hospital->is_active ? 'activated' : 'deactivated';
        return redirect()->back()
            ->with('success', "Hospital {$status} successfully!");
    }
}