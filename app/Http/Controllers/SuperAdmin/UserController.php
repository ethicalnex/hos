<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('hospital')
            ->where('role', '!=', User::ROLE_SUPER_ADMIN)
            ->latest()
            ->paginate(15);

        return view('super-admin.users.index', compact('users'));
    }

    public function create()
    {
        $hospitals = Hospital::active()->get();
        $roles = User::$roles;
        unset($roles[User::ROLE_SUPER_ADMIN]); // Remove super admin from options

        return view('super-admin.users.create', compact('hospitals', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:' . implode(',', array_keys(User::$roles)),
            'hospital_id' => 'required|exists:hospitals,id',
            'phone' => 'nullable|string',
            'license_number' => 'nullable|string',
            'specialization' => 'nullable|string',
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'hospital_id' => $request->hospital_id,
                'phone' => $request->phone,
                'license_number' => $request->license_number,
                'specialization' => $request->specialization,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            return redirect()->route('super-admin.users.index')
                ->with('success', 'User created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create user: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(User $user)
    {
        // Prevent editing super admins
        if ($user->isSuperAdmin()) {
            abort(403, 'Cannot edit super administrator accounts.');
        }

        $hospitals = Hospital::active()->get();
        $roles = User::$roles;
        unset($roles[User::ROLE_SUPER_ADMIN]);

        return view('super-admin.users.edit', compact('user', 'hospitals', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->isSuperAdmin()) {
            abort(403, 'Cannot update super administrator accounts.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:' . implode(',', array_keys(User::$roles)),
            'hospital_id' => 'required|exists:hospitals,id',
            'phone' => 'nullable|string',
            'license_number' => 'nullable|string',
            'specialization' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $user->update($request->only([
            'name', 'email', 'role', 'hospital_id', 'phone', 
            'license_number', 'specialization', 'is_active'
        ]));

        return redirect()->route('super-admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    public function toggle(User $user)
    {
        if ($user->isSuperAdmin()) {
            abort(403, 'Cannot modify super administrator accounts.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->back()
            ->with('success', "User {$status} successfully!");
    }
}