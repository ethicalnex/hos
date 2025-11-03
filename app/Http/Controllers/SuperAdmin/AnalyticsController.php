<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Basic Statistics
        $stats = [
            'total_hospitals' => Hospital::count(),
            'active_hospitals' => Hospital::active()->count(),
            'total_staff' => User::staff()->count(),
            'total_patients' => User::where('role', User::ROLE_PATIENT)->count(),
            'recent_hospitals' => Hospital::withCount('users')->latest()->take(5)->get(),
        ];

        // Hospital Growth Chart Data (Last 6 months)
        $hospitalGrowth = Hospital::selectRaw('
            YEAR(created_at) as year,
            MONTH(created_at) as month,
            COUNT(*) as count
        ')
        ->where('created_at', '>=', Carbon::now()->subMonths(6))
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();

        // User Distribution by Role
        $userDistribution = User::select('role', DB::raw('COUNT(*) as count'))
            ->where('role', '!=', User::ROLE_SUPER_ADMIN)
            ->groupBy('role')
            ->get()
            ->pluck('count', 'role');

        // Hospital with most staff
        $topHospitals = Hospital::withCount(['users as staff_count' => function($query) {
            $query->staff();
        }])->orderBy('staff_count', 'desc')->take(5)->get();

        return view('super-admin.analytics.index', compact(
            'stats', 
            'hospitalGrowth', 
            'userDistribution',
            'topHospitals'
        ));
    }

    public function reports()
    {
        $hospitals = Hospital::active()->get();
        
        return view('super-admin.analytics.reports', compact('hospitals'));
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:hospitals,users,financial,system',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'hospital_id' => 'nullable|exists:hospitals,id',
            'format' => 'required|in:pdf,excel,html',
        ]);

        // For now, return a simple response
        return redirect()->back()->with('info', 'Report generation will be implemented in Phase 6');
    }
}