<?php
namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(): View
    {
        // Get appointments in the nurse's hospital for today and future
        $appointments = Appointment::where('hospital_id', auth()->user()->hospital_id)
            ->where('scheduled_time', '>=', now()->startOfDay())
            ->with(['patient', 'doctor'])
            ->orderBy('scheduled_time')
            ->paginate(15);

        return view('nurse.appointments.index', compact('appointments'));
    }
}