<?php
namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(): View
    {
        $appointments = Appointment::where('doctor_id', auth()->id())
            ->where('hospital_id', auth()->user()->hospital_id)
            ->with('patient')
            ->latest()
            ->paginate(10);

        return view('doctor.appointments.index', compact('appointments'));
    }

    public function calendar(): View
    {
        $schedules = \App\Models\DoctorSchedule::where('doctor_id', auth()->id())
            ->where('hospital_id', auth()->user()->hospital_id)
            ->get();

        $appointments = Appointment::where('doctor_id', auth()->id())
            ->where('hospital_id', auth()->user()->hospital_id)
            ->where('scheduled_time', '>=', now())
            ->get();

        return view('doctor.appointments.calendar', compact('schedules', 'appointments'));
    }

    public function confirm(Appointment $appointment)
    {
        if ($appointment->hospital_id !== auth()->user()->hospital_id) {
            abort(403);
        }
        $appointment->update(['status' => 'confirmed']);
        return back()->with('success', 'Appointment confirmed!');
    }

    public function complete(Appointment $appointment)
    {
        if ($appointment->hospital_id !== auth()->user()->hospital_id) {
            abort(403);
        }
        $appointment->update(['status' => 'completed']);
        return back()->with('success', 'Appointment marked as completed!');
    }
}