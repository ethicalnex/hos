<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    // ✅ ADD THIS METHOD
    public function redirectTo($user)
    {
        return match($user->role) {
            'super_admin' => route('super-admin.dashboard'),
            'admin' => route('hospital.dashboard'),
            'doctor' => route('doctor.dashboard'),
            'nurse' => route('nurse.dashboard'),
            'lab_technician' => route('lab-technician.dashboard'),             
            'pharmacist' => route('pharmacy.dashboard'),
            'receptionist' => route('reception.dashboard'),
            'patient' => route('patient.home'),
            default => '/'
        };
    }
    
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Your account is deactivated.'],
            ]);
        }

        Auth::login($user, $request->filled('remember'));

        return match($user->role) {
            'super_admin' => redirect()->route('super-admin.dashboard'),
            'admin' => redirect()->route('hospital.dashboard'),
            'doctor' => redirect()->route('doctor.dashboard'),
            'nurse' => redirect()->route('nurse.dashboard'),
            'lab_technician' => redirect()->route('lab-technician.dashboard'),
            'pharmacist' => redirect()->route('pharmacy.dashboard'),
            'receptionist' => redirect()->route('reception.dashboard'),
            'patient' => redirect()->route('patient.home'),
            default => redirect('/')
        };
    }

    public function logout(Request $request)
    {
        // Clear all session data
        $request->session()->flush();
        $request->session()->regenerateToken();
        
        // Logout user
        Auth::logout();
        
        // Redirect to login with success message
        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}