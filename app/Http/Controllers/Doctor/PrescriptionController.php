<?php
namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\PharmacyItem;
use App\Models\User;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index()
    {
        $prescriptions = Prescription::where('doctor_id', auth()->id())
            ->with(['patient', 'items.item'])
            ->latest()
            ->paginate(15);
        return view('doctor.prescriptions.index', compact('prescriptions'));
    }

    public function create()
    {
        $patients = User::where('hospital_id', auth()->user()->hospital_id)
            ->where('role', 'patient')
            ->where('is_active', true)
            ->with('patient')
            ->get();
            
        $items = PharmacyItem::where('hospital_id', auth()->user()->hospital_id)
            ->where('is_active', true)
            ->get();
            
        return view('doctor.prescriptions.create', compact('patients', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id,role,patient,hospital_id,' . auth()->user()->hospital_id,
            'diagnosis' => 'nullable|string',
            'valid_until' => 'nullable|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:pharmacy_items,id,hospital_id,' . auth()->user()->hospital_id,
            'items.*.dosage' => 'required|string',
            'items.*.frequency' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.instructions' => 'nullable|string',
        ]);

        $prescription = Prescription::create([
            'hospital_id' => auth()->user()->hospital_id,
            'patient_id' => $request->patient_id,
            'doctor_id' => auth()->id(),
            'diagnosis' => $request->diagnosis,
            'valid_until' => $request->valid_until,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        foreach ($request->items as $itemData) {
            PrescriptionItem::create([
                'prescription_id' => $prescription->id,
                'item_id' => $itemData['item_id'],
                'dosage' => $itemData['dosage'],
                'frequency' => $itemData['frequency'],
                'quantity' => $itemData['quantity'],
                'instructions' => $itemData['instructions'],
                'is_dispensed' => false,
            ]);
        }

        return redirect()->route('doctor.prescriptions.index')
            ->with('success', 'Prescription created successfully!');
    }
}