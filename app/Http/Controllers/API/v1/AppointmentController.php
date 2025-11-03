<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $appointments = Appointment::where('hospital_id', $request->user()->hospital_id)
            ->with(['patient', 'doctor'])
            ->orderBy('scheduled_time', 'desc')
            ->get();

        return response()->json($appointments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id,role,patient,hospital_id,' . $request->user()->hospital_id,
            'doctor_id' => 'required|exists:users,id,role,doctor,hospital_id,' . $request->user()->hospital_id,
            'scheduled_time' => 'required|date',
            'reason' => 'nullable|string',
        ]);

        $appointment = Appointment::create([
            'hospital_id' => $request->user()->hospital_id,
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'scheduled_time' => $request->scheduled_time,
            'reason' => $request->reason,
            'status' => 'scheduled',
        ]);

        return response()->json([
            'message' => 'Appointment created successfully.',
            'appointment' => $appointment,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::where('hospital_id', $request->user()->hospital_id)->findOrFail($id);

        $request->validate([
            'status' => 'required|in:scheduled,confirmed,cancelled,completed',
            'notes' => 'nullable|string',
        ]);

        $appointment->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'message' => 'Appointment updated successfully.',
            'appointment' => $appointment,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $appointment = Appointment::where('hospital_id', $request->user()->hospital_id)->findOrFail($id);
        $appointment->delete();

        return response()->json([
            'message' => 'Appointment deleted successfully.',
        ]);
    }
}