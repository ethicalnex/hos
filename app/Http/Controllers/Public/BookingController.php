<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\DoctorSchedule;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function selectDepartment($hospital)
    {
        $hospital = \App\Models\Hospital::where('slug', $hospital)->firstOrFail();
        $departments = $hospital->departments()->where('is_active', true)->get();
        return view('public.booking.select-department', compact('hospital', 'departments'));
    }

    public function selectService($hospital, Department $department)
    {
        $hospital = \App\Models\Hospital::where('slug', $hospital)->firstOrFail();
        $services = Service::where('hospital_id', $hospital->id)
            ->where('department_id', $department->id)
            ->where('is_active', true)
            ->get();
        return view('public.booking.select-service', compact('hospital', 'department', 'services'));
    }

    public function selectDoctor($hospital, Service $service)
    {
        $hospital = \App\Models\Hospital::where('slug', $hospital)->firstOrFail();
        $doctors = User::where('hospital_id', $hospital->id)
            ->where('role', 'doctor')
            ->where('is_active', true)
            ->get();
        return view('public.booking.select-doctor', compact('hospital', 'service', 'doctors'));
    }

    public function selectTime($hospital, User $doctor)
    {
        $hospital = \App\Models\Hospital::where('slug', $hospital)->firstOrFail();
        $service = session('booking_service');
        $date = request('date', now()->format('Y-m-d'));
        
        // Get doctor's schedule for the day
        $dayOfWeek = strtolower(\Carbon\Carbon::parse($date)->format('l'));
        $schedule = DoctorSchedule::where('doctor_id', $doctor->id)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        $availableSlots = [];
        if ($schedule && $schedule->is_available) {
            $start = \Carbon\Carbon::parse($date . ' ' . $schedule->start_time);
            $end = \Carbon\Carbon::parse($date . ' ' . $schedule->end_time);
            $duration = $service ? $service->duration : 30;

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

    public function store(Request $request, $hospital)
    {
        $hospital = \App\Models\Hospital::where('slug', $hospital)->firstOrFail();
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'doctor_id' => 'required|exists:users,id,role,doctor',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
        ]);

        $datetime = $request->date . ' ' . $request->time;
        $service = Service::findOrFail($request->service_id);

        $appointment = Appointment::create([
            'hospital_id' => $hospital->id,
            'patient_id' => null, // Will be set after payment
            'doctor_id' => $request->doctor_id,
            'service_id' => $service->id,
            'scheduled_time' => $datetime,
            'duration' => $service->duration,
            'reason' => $service->name,
            'status' => 'pending_payment',
        ]);

        // Store in session for payment
        session(['booking_appointment_id' => $appointment->id]);

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
        $appointment = Appointment::findOrFail(session('booking_appointment_id'));

        // Validate payment method
        $request->validate([
            'payment_method' => 'required|in:paystack,flutterwave',
        ]);

        // Create patient
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

        // Create patient profile
        $patient->patient()->create([
            'hospital_id' => $hospital->id,
            'first_name' => explode(' ', $request->name)[0],
            'last_name' => implode(' ', array_slice(explode(' ', $request->name), 1)),
            'phone' => $request->phone,
            'email' => $request->email,
            'date_of_birth' => $request->date_of_birth ?? now()->subYears(30),
            'gender' => $request->gender ?? 'other',
            'medical_record_number' => Patient::generateMedicalRecordNumber($hospital->id),
        ]);

        // Update appointment
        $appointment->update(['patient_id' => $patient->id]);

        // Create payment record
        $payment = Payment::create([
            'hospital_id' => $hospital->id,
            'user_id' => $patient->id,
            'payment_type' => 'appointment',
            'payment_gateway' => $request->payment_method,
            'reference' => (string) Str::uuid(),
            'amount' => $appointment->service->price * 100, // in kobo
            'currency' => 'NGN',
            'status' => 'pending',
            'metadata' => [
                'appointment_id' => $appointment->id,
                'hospital_id' => $hospital->id,
            ],
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