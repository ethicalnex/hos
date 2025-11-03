<?php
namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::where('hospital_id', auth()->user()->hospital_id)
            ->with(['patient', 'doctor', 'service'])
            ->latest()
            ->paginate(20);
        return view('receptionist.appointments.index', compact('appointments'));
    }

    public function confirm(Appointment $appointment)
    {
        if ($appointment->hospital_id !== auth()->user()->hospital_id) abort(403);
        $appointment->update(['status' => 'confirmed']);
        return back()->with('success', 'Appointment confirmed!');
    }
}