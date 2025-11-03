<?php
namespace App\Http\Controllers\LabTechnician;

use App\Http\Controllers\Controller;
use App\Models\LabOrder;
use App\Models\LabOrderTest;
use Illuminate\Http\Request;

class LabResultController extends Controller
{
    public function index()
    {
        $hospital = auth()->user()->hospital;
        
        if (!$hospital->hasFeature('lab')) {
            return redirect()->route('lab-technician.dashboard')->with('error', 'Lab feature is not available in your current plan.');
        }

        $orders = LabOrder::where('hospital_id', auth()->user()->hospital_id)
            ->where('status', '!=', 'cancelled')
            ->with(['patient', 'doctor', 'tests.test'])
            ->latest()
            ->paginate(15);
        return view('lab-technician.lab.results.index', compact('orders'));
    }

    public function create(LabOrder $order)
    {
        if ($order->hospital_id !== auth()->user()->hospital_id) {
            abort(403);
        }
        return view('lab-technician.lab.results.create', compact('order'));
    }

    public function store(Request $request, LabOrder $order)
    {
        if ($order->hospital_id !== auth()->user()->hospital_id) {
            abort(403);
        }

        $request->validate([
            'results' => 'required|array',
            'results.*.test_id' => 'required|exists:lab_tests,id,hospital_id,' . auth()->user()->hospital_id,
            'results.*.result_value' => 'nullable|numeric',
            'results.*.notes' => 'nullable|string',
        ]);

        foreach ($request->results as $resultData) {
            $orderTest = LabOrderTest::where('order_id', $order->id)
                ->where('test_id', $resultData['test_id'])
                ->first();

            if ($orderTest) {
                $orderTest->update([
                    'result_value' => $resultData['result_value'] ?? null,
                    'notes' => $resultData['notes'] ?? null,
                    'is_completed' => true,
                ]);
            }
        }

        // Check if all tests are completed
        $completedTests = $order->tests->where('is_completed', true)->count();
        $totalTests = $order->tests->count();

        if ($completedTests === $totalTests) {
            $order->update(['status' => 'completed', 'lab_technician_id' => auth()->id()]);
        } else {
            $order->update(['status' => 'in_progress', 'lab_technician_id' => auth()->id()]);
        }

        return redirect()->route('lab-technician.lab.results.index')
            ->with('success', 'Lab results submitted successfully!');
    }
}