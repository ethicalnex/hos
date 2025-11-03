<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::with('hospital')
            ->where('hospital_id', auth()->user()->hospital_id)
            ->where('role', '!=', User::ROLE_PATIENT)
            ->latest()
            ->paginate(15);

        return view('hospital.staff.index', compact('staff'));
    }

    public function create()
    {
        $roles = [
            User::ROLE_DOCTOR => 'Doctor',
            User::ROLE_NURSE => 'Nurse',
            User::ROLE_LAB_TECH => 'Lab Technician',
            User::ROLE_PHARMACIST => 'Pharmacist',
            User::ROLE_RECEPTIONIST => 'Receptionist',
        ];

        return view('hospital.staff.create', compact('roles'));
    }

    

    public function store(Request $request)
    {
        $allowedRoles = [
            User::ROLE_DOCTOR,
            User::ROLE_NURSE,
            User::ROLE_LAB_TECH,
            User::ROLE_PHARMACIST,
            User::ROLE_RECEPTIONIST,
        ];

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => ['required', Rule::in($allowedRoles)],
            'phone' => 'nullable|string',
            'license_number' => 'nullable|string',
            'specialization' => 'nullable|string',
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role, // ✅ Now passes validation
                'hospital_id' => auth()->user()->hospital_id,
                'phone' => $request->phone,
                'license_number' => $request->license_number,
                'specialization' => $request->specialization,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            return redirect()->route('hospital.staff.index')
                ->with('success', 'Staff member created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create staff member: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(User $user)
    {
        // Ensure the user belongs to the same hospital
        if ($user->hospital_id !== auth()->user()->hospital_id) {
            abort(403, 'Unauthorized action.');
        }

        $roles = [
            User::ROLE_DOCTOR => 'Doctor',
            User::ROLE_NURSE => 'Nurse',
            User::ROLE_LAB_TECH => 'Lab Technician',
            User::ROLE_PHARMACIST => 'Pharmacist',
            User::ROLE_RECEPTIONIST => 'Receptionist',
        ];

        return view('hospital.staff.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->hospital_id !== auth()->user()->hospital_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:' . implode(',', array_keys([
                User::ROLE_DOCTOR,
                User::ROLE_NURSE,
                User::ROLE_LAB_TECH,
                User::ROLE_PHARMACIST,
                User::ROLE_RECEPTIONIST,
            ])),
            'phone' => 'nullable|string',
            'license_number' => 'nullable|string',
            'specialization' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $user->update($request->only([
            'name', 'email', 'role', 'phone', 
            'license_number', 'specialization', 'is_active'
        ]));

        return redirect()->route('hospital.staff.index')
            ->with('success', 'Staff member updated successfully!');
    }

    public function toggle(User $user)
    {
        if ($user->hospital_id !== auth()->user()->hospital_id) {
            abort(403, 'Unauthorized action.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->back()
            ->with('success', "Staff member {$status} successfully!");
    }
}