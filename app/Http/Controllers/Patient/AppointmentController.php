<?php
namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(): View
    {
        $appointments = Appointment::where('patient_id', auth()->id())
            ->with('doctor', 'hospital')
            ->latest()
            ->paginate(10);

        return view('patient.appointments.index', compact('appointments'));
    }

    public function create(): View
    {
        $doctors = User::where('hospital_id', auth()->user()->hospital_id)
            ->where('role', 'doctor')
            ->where('is_active', true)
            ->get();

        return view('patient.appointments.book', compact('doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id,role,doctor',
            'scheduled_time' => 'required|date|after:now',
            'reason' => 'nullable|string',
        ]);

        Appointment::create([
            'hospital_id' => auth()->user()->hospital_id,
            'patient_id' => auth()->id(),
            'doctor_id' => $request->doctor_id,
            'scheduled_time' => $request->scheduled_time,
            'reason' => $request->reason,
            'status' => 'scheduled',
        ]);

        return redirect()->route('patient.appointments.index')
            ->with('success', 'Appointment booked successfully!');
    }

    public function cancel(Appointment $appointment)
    {
        if ($appointment->patient_id !== auth()->id()) abort(403);
        $appointment->update(['status' => 'cancelled']);
        return back()->with('success', 'Appointment cancelled.');
    }
}