<?php
namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Hospital;        // ← ADD THIS
use App\Models\Department;
use App\Models\DoctorSchedule;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function selectDepartment($hospital)
    {
        $hospital = \App\Models\Hospital::where('slug', $hospital)->firstOrFail();
        $departments = $hospital->departments()->where('is_active', true)->get();
        return view('public.booking.select-department', compact('hospital', 'departments'));
    }

    public function selectService($hospital, $departmentId)
	{
	    $hospital = Hospital::where('slug', $hospital)->firstOrFail();
	    $department = Department::where('id', $departmentId)
	        ->where('hospital_id', $hospital->id)
	        ->firstOrFail();
	    
	    $services = Service::where('hospital_id', $hospital->id)
	        ->where('department_id', $department->id)
	        ->where('is_active', true)
	        ->get();
	        
	    return view('public.booking.select-service', compact('hospital', 'department', 'services'));
	}

    public function selectDoctor($hospital, $serviceId)
    {
        $hospital = Hospital::where('slug', $hospital)->firstOrFail();
        $service = Service::where('id', $serviceId)
            ->where('hospital_id', $hospital->id)
            ->firstOrFail();
        
        $doctors = User::where('hospital_id', $hospital->id)
            ->where('role', 'doctor')
            ->where('is_active', true)
            ->get();
            
        return view('public.booking.select-doctor', compact('hospital', 'service', 'doctors'));
    }

   public function selectTime($hospital, $doctorId)
    {
        $hospital = Hospital::where('slug', $hospital)->firstOrFail();
        $doctor = User::where('id', $doctorId)
            ->where('hospital_id', $hospital->id)
            ->where('role', 'doctor')
            ->firstOrFail();
        
        $serviceId = request('service_id');
        $service = Service::where('id', $serviceId)
            ->where('hospital_id', $hospital->id)
            ->firstOrFail();
        
        $date = request('date', now()->format('Y-m-d'));
        $dayOfWeek = strtolower(\Carbon\Carbon::parse($date)->format('l'));
        
        // Get doctor's schedule for the day
        $schedule = DoctorSchedule::where('doctor_id', $doctor->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        $availableSlots = [];
        if ($schedule) {
            // ✅ Extract ONLY the time part from Carbon instances
            $startTime = $schedule->start_time->format('H:i');
            $endTime = $schedule->end_time->format('H:i');
            
            $start = \Carbon\Carbon::parse($date . ' ' . $startTime);
            $end = \Carbon\Carbon::parse($date . ' ' . $endTime);
            $duration = $service->duration;

            while ($start->copy()->addMinutes($duration)->lte($end)) {
                // Check if slot is already booked
                $isBooked = Appointment::where('doctor_id', $doctor->id)
                    ->whereDate('scheduled_time', $date)
                    ->whereBetween('scheduled_time', [$start, $start->copy()->addMinutes($duration)])
                    ->exists();

                if (!$isBooked) {
                    $availableSlots[] = $start->format('H:i');
                }
                $start->addMinutes($duration);
            }
        }

        return view('public.booking.select-time', compact('hospital', 'doctor', 'service', 'date', 'availableSlots'));
    }

    public function store(Request $request, $hospitalSlug)
    {
        // ✅ Resolve hospital by slug (not use as object)
        $hospital = Hospital::where('slug', $hospitalSlug)->firstOrFail();
        
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'doctor_id' => 'required|exists:users,id,role,doctor',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
        ]);

        $datetime = $request->date . ' ' . $request->time;
        $service = Service::findOrFail($request->service_id);

        // Store appointment data in session for offline payment
        $appointmentData = [
            'hospital_id' => $hospital->id, // ✅ Now $hospital is a model
            'doctor_id' => $request->doctor_id,
            'service_id' => $service->id,
            'scheduled_time' => $datetime,
            'duration' => $service->duration,
            'reason' => $service->name,
        ];

        // Create appointment and store ID in session
        $appointment = Appointment::create(array_merge($appointmentData, [
            'patient_id' => null,
            'status' => 'pending_payment',
        ]));

        session(['booking_appointment_id' => $appointment->id]);

        // Redirect to payment page
        return redirect()->route('booking.payment', ['hospital' => $hospital->slug, 'appointment' => $appointment]);
    }

    public function payment($hospital, Appointment $appointment)
    {
        $hospital = \App\Models\Hospital::where('slug', $hospital)->firstOrFail();
        return view('public.booking.payment', compact('hospital', 'appointment'));
    }

    public function processPayment(Request $request, $hospitalSlug)
    {
        $hospital = Hospital::where('slug', $hospitalSlug)->firstOrFail();
        
        // ✅ CHECK IF EMAIL ALREADY EXISTS
        $existingUser = User::where('email', $request->email)->first();
        
        if ($existingUser) {
            // If user exists but is not a patient, reject
            if ($existingUser->role !== 'patient') {
                return back()->withErrors(['email' => 'This email is already registered with a different account type.']);
            }
            
            // If user exists and is a patient, use existing user
            $patient = $existingUser;
        } else {
            // Create new patient user
            $patient = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => bcrypt(Str::random(12)),
                'role' => 'patient',
                'hospital_id' => $hospital->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }

    // Create or update patient profile
    $patientProfile = $patient->patient()->firstOrNew(['user_id' => $patient->id]);
    $patientProfile->fill([
        'hospital_id' => $hospital->id,
        'first_name' => explode(' ', $request->name)[0],
        'last_name' => implode(' ', array_slice(explode(' ', $request->name), 1)),
        'phone' => $request->phone,
        'email' => $request->email,
        'date_of_birth' => $request->date_of_birth ?? now()->subYears(30),
        'gender' => $request->gender ?? 'other',
        'emergency_contact_name' => $request->emergency_contact_name,
        'emergency_contact_phone' => $request->emergency_contact_phone,
        'medical_record_number' => $patientProfile->medical_record_number ?? \App\Models\Patient::generateMedicalRecordNumber($hospital->id),
    ]);
    $patientProfile->save();
        // Get appointment from session or create new one
        $appointment = Appointment::findOrFail(session('booking_appointment_id'));
        $appointment->update([
            'patient_id' => $patient->id,
            'status' => 'scheduled',
            'payment_method' => 'hospital',
            'paid_at_hospital' => false,
        ]);

        // Send confirmation email (optional)
        // Mail::to($patient->email)->send(new AppointmentConfirmed($appointment));

        // Redirect to success page
        return view('public.booking.success', [
            'hospital' => $hospital,
            'payment_method' => 'hospital'
        ]);

        // Initialize payment with your Phase 7 service
        try {
            $service = PaymentGatewayFactory::make($request->payment_method, $hospital->id);
            $result = $service->initialize([
                'email' => $request->email,
                'amount' => $payment->amount,
                'reference' => $payment->reference,
                'callback_url' => route('booking.payment-callback', ['hospital' => $hospital->slug]),
                'metadata' => $payment->metadata,
            ]);

            // Store payment reference in session for callback
            session(['payment_reference' => $payment->reference]);

            return redirect($result['authorization_url']);
        } catch (\Exception $e) {
            \Log::error('Payment initialization failed', [
                'error' => $e->getMessage(),
                'hospital' => $hospital->id,
                'appointment' => $appointment->id
            ]);

            return back()->withErrors('Payment initialization failed. Please try again.');
        }
    }
    public function paymentCallback(Request $request, $hospitalSlug)
    {
        $reference = $request->query('reference');
        $payment = Payment::where('reference', $reference)->first();

        if (!$payment) {
            return redirect('/')->withErrors('Invalid payment reference.');
        }

        // Verify payment status via webhook or API (simplified)
        $payment->update(['status' => 'successful']);
        $appointment = Appointment::find($payment->metadata['appointment_id']);
        $appointment->update(['status' => 'scheduled', 'payment_id' => $reference]);

        // Send confirmation email
        \Mail::to($appointment->patient->email)->send(new \App\Mail\AppointmentConfirmed($appointment));

        return redirect()->route('booking.success', ['hospital' => $hospitalSlug])
            ->with('success', 'Payment successful! Your appointment is confirmed.');
    }
}