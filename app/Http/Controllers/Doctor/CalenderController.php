<?php
namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function index(): View
    {
        $appointments = Appointment::where('doctor_id', auth()->id())
            ->where('hospital_id', auth()->user()->hospital_id)
            ->where('scheduled_time', '>=', now())
            ->get()
            ->map(function ($appt) {
                return [
                    'title' => $appt->patient->name,
                    'start' => $appt->scheduled_time->format('c'),
                    'backgroundColor' => $appt->status === 'completed' ? '#28a745' : '#007bff',
                    'borderColor' => $appt->status === 'completed' ? '#28a745' : '#007bff',
                ];
            });

        return view('doctor.calendar.index', compact('appointments'));
    }
}