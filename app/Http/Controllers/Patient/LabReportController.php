<?php
namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\LabOrder;
use App\Models\LabReport;
use Illuminate\Http\Request;
use PDF;

class LabReportController extends Controller
{
    public function index()
    {
        $orders = LabOrder::where('patient_id', auth()->id())
            ->where('status', 'completed')
            ->with(['doctor', 'tests.test', 'report'])
            ->latest()
            ->paginate(10);
        return view('patient.lab.reports.index', compact('orders'));
    }

    public function show(LabOrder $order)
    {
        if ($order->patient_id !== auth()->id()) {
            abort(403);
        }
        
        if (!$order->report) {
            // Generate PDF report
            $pdf = PDF::loadView('patient.lab.reports.pdf', compact('order'));
            $filename = 'lab-report-' . $order->id . '.pdf';
            $path = 'lab-reports/' . $filename;
            \Storage::put('public/' . $path, $pdf->output());
            
            LabReport::create([
                'order_id' => $order->id,
                'report_path' => $path,
                'is_shared_with_patient' => true,
            ]);
        }

        return view('patient.lab.reports.show', compact('order'));
    }

    public function download(LabOrder $order)
    {
        if ($order->patient_id !== auth()->id()) {
            abort(403);
        }
        
        if (!$order->report) {
            abort(404);
        }

        return response()->file(storage_path('app/public/' . $order->report->report_path));
    }
}