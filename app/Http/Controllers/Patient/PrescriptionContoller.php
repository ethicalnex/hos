<?php
namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index()
    {
        $prescriptions = Prescription::where('patient_id', auth()->id())
            ->with(['doctor', 'items.item'])
            ->latest()
            ->paginate(15);
        return view('patient.prescriptions.index', compact('prescriptions'));
    }

    public function show(Prescription $prescription)
    {
        if ($prescription->patient_id !== auth()->id()) {
            abort(403);
        }
        
        return view('patient.prescriptions.show', compact('prescription'));
    }
}