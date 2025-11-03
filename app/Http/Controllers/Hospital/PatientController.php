<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->get('q');
        $patients = Patient::where('hospital_id', auth()->user()->hospital_id);

        if ($query) {
            $patients = $patients->where(function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('medical_record_number', 'like', "%{$query}%")
                  ->orWhere('phone', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            });
        }

        $patients = $patients->latest()->paginate(15)->appends(['q' => $query]);

        return view('hospital.patients.index', compact('patients', 'query'));
    }

    public function show(Patient $patient)
    {
        // Ensure patient belongs to this hospital
        if ($patient->hospital_id !== auth()->user()->hospital_id) {
            abort(403);
        }

        return view('hospital.patients.show', compact('patient'));
    }
}