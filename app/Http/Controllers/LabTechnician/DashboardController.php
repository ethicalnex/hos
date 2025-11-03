<?php
namespace App\Http\Controllers\LabTechnician;

use App\Http\Controllers\Controller;
use App\Models\LabOrder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingOrders = LabOrder::where('hospital_id', auth()->user()->hospital_id)
            ->where('status', 'pending')
            ->count();
            
        $completedToday = LabOrder::where('hospital_id', auth()->user()->hospital_id)
            ->where('status', 'completed')
            ->whereDate('updated_at', now()->toDateString())
            ->count();
            
        $totalTests = \App\Models\LabOrderTest::whereHas('order', function ($q) {
            $q->where('hospital_id', auth()->user()->hospital_id);
        })->count();

        return view('lab-technician.dashboard', compact(
            'pendingOrders', 'completedToday', 'totalTests'
        ));
    }
}