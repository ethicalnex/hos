<?php
namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;

class DoctorScheduleController extends Controller
{
    public function index()
    {
        $doctors = User::where('hospital_id', auth()->user()->hospital_id)
            ->where('role', 'doctor')
            ->with('schedules')
            ->get();
            
        return view('hospital.doctor-schedules.index', compact('doctors'));
    }

    public function create()
    {
        $doctors = User::where('hospital_id', auth()->user()->hospital_id)
            ->where('role', 'doctor')
            ->get();
            
        $days = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday', 
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday'
        ];
        
        return view('hospital.doctor-schedules.create', compact('doctors', 'days'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id,role,doctor,hospital_id,' . auth()->user()->hospital_id,
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'slot_duration' => 'required|integer|min:10|max:120',
        ]);

        // Delete existing schedule for this doctor/day
        DoctorSchedule::where('doctor_id', $request->doctor_id)
            ->where('day_of_week', $request->day_of_week)
            ->delete();

        DoctorSchedule::create([
            'hospital_id' => auth()->user()->hospital_id,
            'doctor_id' => $request->doctor_id,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'slot_duration' => $request->slot_duration,
            'is_available' => true,
        ]);

        return redirect()->route('hospital.doctor-schedules.index')
            ->with('success', 'Doctor schedule created successfully!');
    }

    public function toggle(Request $request, DoctorSchedule $schedule)
    {
        if ($schedule->hospital_id !== auth()->user()->hospital_id) {
            abort(403);
        }
        
        $schedule->update(['is_available' => !$schedule->is_available]);
        
        return back()->with('success', 'Schedule availability updated!');
    }

    public function destroy(DoctorSchedule $schedule)
    {
        if ($schedule->hospital_id !== auth()->user()->hospital_id) {
            abort(403);
        }
        
        $schedule->delete();
        
        return back()->with('success', 'Schedule deleted successfully!');
    }
}