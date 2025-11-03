<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
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

        return view('hospital.dashboard.index', compact('subscriptionStatus'));
        
        // Safe statistics with fallbacks
        $stats = [
            'total_staff' => User::where('hospital_id', $hospital->id)
                ->where('role', '!=', User::ROLE_PATIENT)
                ->count(),
            'total_patients' => User::where('hospital_id', $hospital->id)
                ->where('role', User::ROLE_PATIENT)
                ->count(),
            'total_doctors' => User::where('hospital_id', $hospital->id)
                ->where('role', User::ROLE_DOCTOR)
                ->count(),
            'today_appointments' => 0, // Default for now until appointments are implemented
        ];

        // Staff by role with safe data
        $staffByRole = User::where('hospital_id', $hospital->id)
            ->where('role', '!=', User::ROLE_PATIENT)
            ->select('role', DB::raw('COUNT(*) as count'))
            ->groupBy('role')
            ->get()
            ->pluck('count', 'role');

        // Recent appointments - empty for now
        $recentAppointments = collect();

        return view('hospital.dashboard.index', compact('stats', 'recentAppointments', 'staffByRole'));
    }
}