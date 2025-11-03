<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\HospitalMetric;
use App\Models\AnalyticsReport;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        $hospital = auth()->user()->hospital;
        
        // Get today's metrics
        $today = Carbon::today();
        $metric = HospitalMetric::where('hospital_id', $hospital->id)
            ->where('date', $today)
            ->first();

        if (!$metric) {
            $metric = $this->generateMetrics($hospital, $today);
        }

        // Get last 7 days metrics for chart
        $last7Days = HospitalMetric::where('hospital_id', $hospital->id)
            ->where('date', '>=', $today->copy()->subDays(6))
            ->orderBy('date')
            ->get();

        // Get key stats
        $stats = [
            'total_patients' => User::where('hospital_id', $hospital->id)
                ->where('role', 'patient')
                ->count(),
            'total_staff' => User::where('hospital_id', $hospital->id)
                ->whereIn('role', ['doctor', 'nurse', 'lab_technician', 'pharmacist'])
                ->count(),
            'total_appointments' => Appointment::where('hospital_id', $hospital->id)
                ->count(),
            'completed_appointments' => Appointment::where('hospital_id', $hospital->id)
                ->where('status', 'completed')
                ->count(),
            'total_revenue' => Payment::where('hospital_id', $hospital->id)
                ->sum('amount'),
            'active_subscriptions' => $hospital->subscriptions()
                ->where('is_active', true)
                ->count(),
        ];

        return view('hospital.analytics.index', compact('metric', 'last7Days', 'stats'));
    }

    public function reports()
    {
        $hospital = auth()->user()->hospital;
        
        // Get all reports
        $reports = AnalyticsReport::where('hospital_id', $hospital->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('hospital.analytics.reports', compact('reports'));
    }

    public function generateReport(Request $request)
    {
        $hospital = auth()->user()->hospital;
        $type = $request->input('type', 'monthly');

        $data = [];

        switch ($type) {
            case 'daily':
                $start = Carbon::today();
                $end = $start->copy()->endOfDay();
                $title = 'Daily Report';
                break;
            case 'weekly':
                $start = Carbon::now()->startOfWeek();
                $end = Carbon::now()->endOfWeek();
                $title = 'Weekly Report';
                break;
            case 'monthly':
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                $title = 'Monthly Report';
                break;
            case 'yearly':
                $start = Carbon::now()->startOfYear();
                $end = Carbon::now()->endOfYear();
                $title = 'Yearly Report';
                break;
        }

        // Generate report data
        $data['period'] = $title;
        $data['start_date'] = $start->format('Y-m-d');
        $data['end_date'] = $end->format('Y-m-d');

        $data['patients'] = [
            'total' => User::where('hospital_id', $hospital->id)
                ->where('role', 'patient')
                ->count(),
            'new' => User::where('hospital_id', $hospital->id)
                ->where('role', 'patient')
                ->whereDate('created_at', '>=', $start)
                ->count(),
        ];

        $data['appointments'] = [
            'total' => Appointment::where('hospital_id', $hospital->id)
                ->whereBetween('scheduled_time', [$start, $end])
                ->count(),
            'completed' => Appointment::where('hospital_id', $hospital->id)
                ->where('status', 'completed')
                ->whereBetween('scheduled_time', [$start, $end])
                ->count(),
            'cancelled' => Appointment::where('hospital_id', $hospital->id)
                ->where('status', 'cancelled')
                ->whereBetween('scheduled_time', [$start, $end])
                ->count(),
        ];

        $data['revenue'] = [
            'total' => Payment::where('hospital_id', $hospital->id)
                ->whereBetween('created_at', [$start, $end])
                ->sum('amount'),
            'average_per_appointment' => 0,
        ];

        if ($data['appointments']['total'] > 0) {
            $data['revenue']['average_per_appointment'] = $data['revenue']['total'] / $data['appointments']['total'];
        }

        $data['staff'] = [
            'total' => User::where('hospital_id', $hospital->id)
                ->whereIn('role', ['doctor', 'nurse', 'lab_technician', 'pharmacist'])
                ->count(),
            'active' => User::where('hospital_id', $hospital->id)
                ->whereIn('role', ['doctor', 'nurse', 'lab_technician', 'pharmacist'])
                ->where('is_active', true)
                ->count(),
        ];

        // Create report
        $report = AnalyticsReport::create([
            'hospital_id' => $hospital->id,
            'report_type' => $type,
            'data' => $data,
            'notes' => $request->input('notes'),
        ]);

        return redirect()->route('hospital.analytics.reports')
            ->with('success', 'Report generated successfully!');
    }

    private function generateMetrics($hospital, $date)
    {
        $metrics = [
            'hospital_id' => $hospital->id,
            'date' => $date,
            'total_patients' => User::where('hospital_id', $hospital->id)
                ->where('role', 'patient')
                ->count(),
            'new_patients' => User::where('hospital_id', $hospital->id)
                ->where('role', 'patient')
                ->whereDate('created_at', $date)
                ->count(),
            'total_appointments' => Appointment::where('hospital_id', $hospital->id)
                ->count(),
            'completed_appointments' => Appointment::where('hospital_id', $hospital->id)
                ->where('status', 'completed')
                ->count(),
            'cancelled_appointments' => Appointment::where('hospital_id', $hospital->id)
                ->where('status', 'cancelled')
                ->count(),
            'total_revenue' => Payment::where('hospital_id', $hospital->id)
                ->sum('amount'),
            'total_staff' => User::where('hospital_id', $hospital->id)
                ->whereIn('role', ['doctor', 'nurse', 'lab_technician', 'pharmacist'])
                ->count(),
            'active_staff' => User::where('hospital_id', $hospital->id)
                ->whereIn('role', ['doctor', 'nurse', 'lab_technician', 'pharmacist'])
                ->where('is_active', true)
                ->count(),
        ];

        return HospitalMetric::create($metrics);
    }
}