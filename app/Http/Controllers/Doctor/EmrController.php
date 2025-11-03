<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmrController extends Controller
{
    public function index()
    {
        $records = MedicalRecord::where('doctor_id', auth()->id())
            ->with('patient.patient')
            ->latest()
            ->paginate(15);

        return view('doctor.emr.index', compact('records'));
    }

    public function create()
    {
        // Get patients assigned to this doctor
        $patients = Patient::whereHas('appointments', function ($query) {
            $query->where('doctor_id', auth()->id());
        })->with('patient')->get();

        return view('doctor.emr.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id,role,patient',
            'appointment_id' => 'nullable|exists:appointments,id,doctor_id,' . auth()->id(),
            'symptoms' => 'nullable|string',
            'temperature' => 'nullable|string',
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

        return redirect()->route('doctor.emr.show', $record)
            ->with('success', 'EMR record created successfully!');
    }

    public function show(MedicalRecord $record)
    {
        if ($record->doctor_id !== auth()->id()) {
            abort(403);
        }

        return view('doctor.emr.show', compact('record'));
    }

    public function edit(MedicalRecord $record)
    {
        if ($record->doctor_id !== auth()->id()) {
            abort(403);
        }

        $patients = Patient::whereHas('appointments', function ($query) {
            $query->where('doctor_id', auth()->id());
        })->with('patient')->get();

        return view('doctor.emr.edit', compact('record', 'patients'));
    }

    public function update(Request $request, MedicalRecord $record)
    {
        if ($record->doctor_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'patient_id' => 'required|exists:users,id,role,patient',
            'appointment_id' => 'nullable|exists:appointments,id,doctor_id,' . auth()->id(),
            'symptoms' => 'nullable<string>',
            'temperature' => 'nullable<string>',
            'blood_pressure' => 'nullable<string>',
            'pulse' => 'nullable<string>',
            'respiratory_rate' => 'nullable<string>',
            'weight' => 'nullable<string>',
            'height' => 'nullable<string>',
            'diagnosis' => 'required<string>',
            'treatment_plan' => 'required<string>',
            'doctor_notes' => 'nullable<string>',
            'nurse_notes' => 'nullable<string>',
        ]);

        $record->update($request->only([
            'patient_id', 'appointment_id', 'symptoms', 'temperature', 'blood_pressure',
            'pulse', 'respiratory_rate', 'weight', 'height', 'diagnosis',
            'treatment_plan', 'doctor_notes', 'nurse_notes'
        ]));

        return redirect()->route('doctor.emr.show', $record)
            ->with('success', 'EMR record updated successfully!');
    }
}