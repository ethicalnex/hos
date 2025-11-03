<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Models\Hospital;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PatientRegistrationController extends Controller
{
    public function show(Hospital $hospital): View
    {
        if (!$hospital->is_active) {
            abort(404, 'Hospital not accepting patients.');
        }
        return view('patient.register', compact('hospital'));
    }

    public function store(StorePatientRequest $request, Hospital $hospital): RedirectResponse
    {
        if (!$hospital->is_active) {
            abort(403);
        }

        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'patient',
            'hospital_id' => $hospital->id,
            'phone' => $request->phone,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $user->patient()->create([
            'hospital_id' => $hospital->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'blood_type' => $request->blood_type,
            'allergies' => $request->allergies,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'insurance_provider' => $request->insurance_provider,
            'insurance_number' => $request->insurance_number,
            'medical_record_number' => Patient::generateMedicalRecordNumber($hospital->id),
        ]);

        Auth::login($user);

        // ✅ Redirect to PATIENT home, not hospital dashboard
        return redirect()->route('patient.home')
            ->with('success', 'Registration successful! Welcome, ' . $user->name);
    }
}