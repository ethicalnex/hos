<?php
namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use Illuminate\View\View;

class PatientEmrController extends Controller
{
    public function index(): View
    {
        $records = MedicalRecord::where('patient_id', auth()->user()->patient->id)
            ->with(['doctor', 'appointment'])
            ->latest()
            ->paginate(10);

        return view('patient.emr.index', compact('records'));
    }
}