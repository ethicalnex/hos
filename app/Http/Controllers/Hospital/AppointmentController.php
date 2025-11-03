<?php
namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(): View
    {
        $appointments = Appointment::where('hospital_id', auth()->user()->hospital_id)
            ->with(['patient', 'doctor'])
            ->latest()
            ->paginate(20);

        return view('hospital.appointments.index', compact('appointments'));
    }

    public function show(Appointment $appointment): View
    {
        if ($appointment->hospital_id !== auth()->user()->hospital_id) {
            abort(403);
        }
        return view('hospital.appointments.show', compact('appointment'));
    }
}