<?php
namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\LabOrder;
use App\Models\LabTest;
use App\Models\User;
use Illuminate\Http\Request;

class LabOrderController extends Controller
{
    public function index()
    {
        $orders = LabOrder::where('doctor_id', auth()->id())
            ->with(['patient', 'labTechnician', 'tests.test'])
            ->latest()
            ->paginate(15);
        return view('doctor.lab.orders.index', compact('orders'));
    }

    public function create()
    {
        $hospital = auth()->user()->hospital;
        
        if (!$hospital->hasFeature('lab')) {
            return redirect()->route('doctor.dashboard')->with('error', 'Lab feature is not available in your current plan.');
        }

        $patients = User::where('hospital_id', auth()->user()->hospital_id)
            ->where('role', 'patient')
            ->where('is_active', true)
            ->get();
            
        $tests = LabTest::where('hospital_id', auth()->user()->hospital_id)
            ->where('is_active', true)
            ->with('category')
            ->get();
            
        return view('doctor.lab.orders.create', compact('patients', 'tests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id,role,patient,hospital_id,' . auth()->user()->hospital_id,
            'test_ids' => 'required|array|min:1',
            'test_ids.*' => 'exists:lab_tests,id,hospital_id,' . auth()->user()->hospital_id,
            'notes' => 'nullable|string',
        ]);

        $order = LabOrder::create([
            'hospital_id' => auth()->user()->hospital_id,
            'patient_id' => $request->patient_id,
            'doctor_id' => auth()->id(),
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        foreach ($request->test_ids as $testId) {
            $order->tests()->create(['test_id' => $testId]);
        }

        return redirect()->route('doctor.lab.orders.index')
            ->with('success', 'Lab order created successfully!');
    }
}