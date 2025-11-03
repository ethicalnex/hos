<?php
namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use Illuminate\View\View;

class EmrController extends Controller
{
    public function index()
    {
        $result = \App\Helpers\FeatureHelper::check('emr');
        if ($result) return $result;

        $records = \App\Models\MedicalRecord::where('hospital_id', auth()->user()->hospital_id)
            ->with('patient')
            ->latest()
            ->paginate(15);
        return view('hospital.emr.index', compact('records'));
    }

    public function create()
    {
        $result = \App\Helpers\FeatureHelper::check('emr');
        if ($result) return $result;

        $patients = \App\Models\User::where('hospital_id', auth()->user()->hospital_id)
            ->where('role', 'patient')
            ->where('is_active', true)
            ->get();
        return view('hospital.emr.create', compact('patients'));
    }
    public function store(\Illuminate\Http\Request $request)
    {
        $result = \App\Helpers\FeatureHelper::check('emr');
        if ($result) return $result;

        $request->validate([
            'patient_id' => 'required|exists:users,id,role,patient,hospital_id,' . auth()->user()->hospital_id,
            'appointment_id' => 'nullable|exists:appointments,id,patient_id,' . $request->patient_id,
            'symptoms' => 'nullable<string>',
            'temperature' => 'nullable<string>',
            'blood_pressure' => 'nullable<string>',
            'pulse' => 'nullable<string>',
            'respiratory_rate' => 'nullable<string>',
            'weight' => 'nullable<string>',
            'height' => 'nullable<string>',
            'diagnosis' => 'required|string',
            'treatment_plan' => 'required<string>',
            'doctor_notes' => 'nullable<string>',
            'nurse_notes' => 'nullable<string>',
        ]);

        $record = MedicalRecord::create([
            'hospital_id' => auth()->user()->hospital_id,
            'patient_id' => $request->patient_id,
            'appointment_id' => $request->appointment_id,
            'symptoms' => $request->symptoms,
            'temperature' => $request->temperature,
            'blood_pressure' => $request->blood_pressure,
            'pulse' => $request->pulse,
            'respiratory_rate' => $request->respiratory_rate,
            'weight' => $request->weight,
            'height' => $request->height,
            'diagnosis' => $request->diagnosis,
            'treatment_plan' => $request->treatment_plan,
            'doctor_notes' => $request->doctor_notes,
            'nurse_notes' => $request->nurse_notes,
            'doctor_id' => auth()->id(),
        ]);

        return redirect()->route('hospital.emr.show', $record)
            ->with('success', 'EMR record created successfully!');
    }

    public function show(MedicalRecord $record): View
    {
        $result = \App\Helpers\FeatureHelper::check('emr');
        if ($result) return $result;

        if ($record->hospital_id !== auth()->user()->hospital_id) abort(403);
        return view('hospital.emr.show', compact('record'));
    }

    public function edit(MedicalRecord $record): View
    {
        $result = \App\Helpers\FeatureHelper::check('emr');
        if ($result) return $result;

        if ($record->hospital_id !== auth()->user()->hospital_id) abort(403);
        $patients = \App\Models\User::where('hospital_id', auth()->user()->hospital_id)
            ->where('role', 'patient')
            ->where('is_active', true)
            ->get();
        return view('hospital.emr.edit', compact('record', 'patients'));
    }

    public function update(\Illuminate\Http\Request $request, MedicalRecord $record)
    {
        $result = \App\Helpers\FeatureHelper::check('emr');
        if ($result) return $result;

        if ($record->hospital_id !== auth()->user()->hospital_id) abort(403);

        $request->validate([
            'patient_id' => 'required|exists:users,id,role,patient,hospital_id,' . auth()->user()->hospital_id,
            'diagnosis' => 'nullable<string>',
            'treatment' => 'nullable<string>',
            'notes' => 'nullable<string>',
        ]);

        $record->update($request->only(['patient_id', 'diagnosis', 'treatment', 'notes']));

        return redirect()->route('hospital.emr.show', $record)
            ->with('success', 'EMR record updated successfully!');
    }
}