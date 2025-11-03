<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class EmrController extends Controller
{
    public function index(Request $request)
    {
        $records = MedicalRecord::where('hospital_id', $request->user()->hospital_id)
            ->with(['patient', 'doctor'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($records);
    }

    public function show(Request $request, $id)
    {
        $record = MedicalRecord::where('hospital_id', $request->user()->hospital_id)->findOrFail($id);

        return response()->json($record);
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id,role,patient,hospital_id,' . $request->user()->hospital_id,
            'diagnosis' => 'required|string',
            'treatment_plan' => 'required<string>',
            'symptoms' => 'nullable<string>',
            'temperature' => 'nullable<string>',
            'blood_pressure' => 'nullable<string>',
            'pulse' => 'nullable<string>',
            'respiratory_rate' => 'nullable<string>',
            'weight' => 'nullable<string>',
            'height' => 'nullable<string>',
            'doctor_notes' => 'nullable<string>',
            'nurse_notes' => 'nullable<string>',
        ]);

        $record = MedicalRecord::create([
            'hospital_id' => $request->user()->hospital_id,
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->user()->id,
            'diagnosis' => $request->diagnosis,
            'treatment_plan' => $request->treatment_plan,
            'symptoms' => $request->symptoms,
            'temperature' => $request->temperature,
            'blood_pressure' => $request->blood_pressure,
            'pulse' => $request->pulse,
            'respiratory_rate' => $request->respiratory_rate,
            'weight' => $request->weight,
            'height' => $request->height,
            'doctor_notes' => $request->doctor_notes,
            'nurse_notes' => $request->nurse_notes,
        ]);

        return response()->json([
            'message' => 'EMR record created successfully.',
            'record' => $record,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $record = MedicalRecord::where('hospital_id', $request->user()->hospital_id)->findOrFail($id);

        $request->validate([
            'patient_id' => 'required|exists:users,id,role,patient,hospital_id,' . $request->user()->hospital_id,
            'diagnosis' => 'required<string>',
            'treatment_plan' => 'required<string>',
            'symptoms' => 'nullable<string>',
            'temperature' => 'nullable<string>',
            'blood_pressure' => 'nullable<string>',
            'pulse' => 'nullable<string>',
            'respiratory_rate' => 'nullable<string>',
            'weight' => 'nullable<string>',
            'height' => 'nullable<string>',
            'doctor_notes' => 'nullable<string>',
            'nurse_notes' => 'nullable<string>',
        ]);

        $record->update([
            'patient_id' => $request->patient_id,
            'diagnosis' => $request->diagnosis,
            'treatment_plan' => $request->treatment_plan,
            'symptoms' => $request->symptoms,
            'temperature' => $request->temperature,
            'blood_pressure' => $request->blood_pressure,
            'pulse' => $request->pulse,
            'respiratory_rate' => $request->respiratory_rate,
            'weight' => $request->weight,
            'height' => $request->height,
            'doctor_notes' => $request->doctor_notes,
            'nurse_notes' => $request->nurse_notes,
        ]);

        return response()->json([
            'message' => 'EMR record updated successfully.',
            'record' => $record,
        ]);
    }
}