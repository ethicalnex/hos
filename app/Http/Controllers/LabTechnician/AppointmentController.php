<?php
namespace App\Http\Controllers\LabTechnician;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::where('hospital_id', auth()->user()->hospital_id)
            ->whereDate('scheduled_time', now()->toDateString())
            ->with(['patient', 'doctor', 'service'])
            ->orderBy('scheduled_time')
            ->paginate(15);

        return view('lab-technician.appointments.index', compact('appointments'));
    }

    public function show(Appointment $appointment)
    {
        if ($appointment->hospital_id !== auth()->user()->hospital_id) {
            abort(403);
        }
        
        return view('lab-technician.appointments.show', compact('appointment'));
    }
}