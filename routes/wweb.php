<?php
// Include Hospital Admin Routes
require __DIR__.'/hospital-admin.php';
use App\Http\Controllers\InstallController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Hospital\EmrController;
use App\Http\Controllers\PatientEmrController;
use App\Http\Controllers\Auth\LoginController; // ← FULL IMPORT
use App\Http\Controllers\Doctor\AppointmentController as DoctorAppointmentController;
use App\Http\Controllers\Patient\AppointmentController as PatientAppointmentController;
use App\Http\Controllers\Nurse\AppointmentController as NurseAppointmentController;
use App\Http\Controllers\Booking\BookingController;

// Installer Routes
Route::get('/install', [InstallController::class, 'welcome'])->name('install.welcome');
Route::get('/install/requirements', [InstallController::class, 'requirements'])->name('install.requirements');
Route::get('/install/permissions', [InstallController::class, 'permissions'])->name('install.permissions');
Route::get('/install/database', [InstallController::class, 'database'])->name('install.database');
Route::post('/install/test-database', [InstallController::class, 'testDatabase'])->name('install.testDatabase');
Route::post('/install/save-database', [InstallController::class, 'saveDatabase'])->name('install.saveDatabase');
Route::post('/install/process', [InstallController::class, 'install'])->name('install.process');
Route::get('/install/complete', [InstallController::class, 'complete'])->name('install.complete');

// Include Super Admin Routes
require __DIR__.'/super-admin.php';

// Include Auth Routes (we'll create these next)
require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/patient/home', function () {
        return view('patient.home');
    })->name('patient.home');
});


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


    // Patient Profile
    Route::get('/patient/profile', function () {
        if (auth()->user()->role !== 'patient') {
            return redirect()->route('hospital.dashboard');
        }
        return view('patient.profile', [
            'patient' => auth()->user()->patient
        ]);
    })->name('patient.profile');

// Patient self-registration
Route::get('/register/{hospital}', [\App\Http\Controllers\PatientRegistrationController::class, 'show'])->name('patient.register.show');
Route::post('/register/{hospital}', [\App\Http\Controllers\PatientRegistrationController::class, 'store'])->name('patient.register.store');

// Doctor EMR
Route::middleware(['auth', \App\Http\Middleware\Doctor::class])
    ->prefix('doctor')
    ->name('doctor.')
    ->group(function () {
        Route::get('/emr/create/{appointment}', [Doctor\EmrController::class, 'create'])->name('emr.create');
        Route::post('/emr', [Doctor\EmrController::class, 'store'])->name('emr.store');
        Route::get('/emr/{record}', [Doctor\EmrController::class, 'show'])->name('emr.show');
        Route::get('/emr/{record}/edit', [Doctor\EmrController::class, 'edit'])->name('emr.edit');
        Route::put('/emr/{record}', [Doctor\EmrController::class, 'update'])->name('emr.update');
    });


// Public Booking Portal
Route::prefix('book')->name('booking.')->group(function () {
    Route::get('/{hospital}', [BookingController::class, 'selectDepartment'])->name('department');
    Route::get('/{hospital}/department/{department}', [BookingController::class, 'selectService'])->name('service');
    Route::get('/{hospital}/service/{service}', [BookingController::class, 'selectDoctor'])->name('doctor');
    Route::get('/{hospital}/doctor/{doctor}/time', [BookingController::class, 'selectTime'])->name('time');
    Route::post('/{hospital}/appointment', [BookingController::class, 'store'])->name('store');
    Route::get('/{hospital}/payment/{appointment}', [BookingController::class, 'payment'])->name('payment');
    Route::post('/{hospital}/process-payment', [BookingController::class, 'processPayment'])->name('process-payment');
    Route::get('/{hospital}/payment-callback', [BookingController::class, 'paymentCallback'])->name('payment-callback');
});







// Nurse EMR
Route::middleware(['auth', \App\Http\Middleware\Nurse::class])
    ->prefix('nurse')
    ->name('nurse.')
    ->group(function () {
        Route::get('/emr/{record}/notes', [Nurse\EmrController::class, 'editNotes'])->name('emr.notes.edit');
        Route::put('/emr/{record}/notes', [Nurse\EmrController::class, 'updateNotes'])->name('emr.notes.update');
    });

// Patient EMR
Route::middleware(['auth', 'patient'])
    ->prefix('patient')
    ->name('patient.')
    ->group(function () {
        Route::get('/emr', [PatientEmrController::class, 'index'])->name('emr.index');
    });



// Public webhook endpoints (no auth)
Route::post('/webhooks/paystack', [\App\Http\Controllers\WebhookController::class, 'handlePaystack']);
Route::post('/webhooks/flutterwave', [\App\Http\Controllers\WebhookController::class, 'handleFlutterwave']);

// Patient EMR (read-only)
Route::middleware(['auth', 'patient'])->prefix('patient')->group(function () {
    Route::get('/emr', [PatientEmrController::class, 'index'])->name('patient.emr.index');
});

// Patient dashboard (public, but auth required)
Route::middleware(['auth', 'patient'])->group(function () {
    Route::get('/patient/dashboard', function () {
        return view('patient.dashboard');
    })->name('patient.dashboard');
});


// Public route: patient home (shows after login)
Route::get('/patient/home', function () {
    if (auth()->check() && auth()->user()->role === 'patient') {
        return view('patient.home');
    }
    return redirect()->route('login');
})->name('patient.home')->middleware('auth');












// Add this to routes/web.php temporarily
Route::get('/debug-autoload', function() {
    echo "Testing ProfileController autoload...<br>";
    
    // Test 1: Check if file exists
    $filePath = app_path('Http/Controllers/ProfileController.php');
    echo "File exists: " . (file_exists($filePath) ? 'YES' : 'NO') . "<br>";
    
    // Test 2: Check if class can be loaded
    $className = 'App\Http\Controllers\ProfileController';
    echo "Class exists: " . (class_exists($className) ? 'YES' : 'NO') . "<br>";
    
    // Test 3: Try to instantiate
    try {
        $controller = app()->make($className);
        echo "Controller can be instantiated: YES<br>";
    } catch (Exception $e) {
        echo "Controller instantiation failed: " . $e->getMessage() . "<br>";
    }
    
    // Test 4: Check namespace
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        if (strpos($content, 'namespace App\Http\Controllers;') === false) {
            echo "❌ NAMESPACE IS WRONG!<br>";
            echo "File content preview:<br>";
            echo "<pre>" . htmlspecialchars(substr($content, 0, 500)) . "</pre>";
        } else {
            echo "✅ Namespace is correct<br>";
        }
    }
});












// Profile Routes - USE FULL CLASS PATH TEMPORARILY
Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [\App\Http\Controllers\ProfileController::class, 'show'])->name('show');
    Route::get('/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('edit');
    Route::put('/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('update');
    Route::put('/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('password');
});

// Hospital Registration Routes
Route::get('/register-hospital', [HospitalRegistrationController::class, 'showRegistrationForm'])->name('hospital.registration');
Route::post('/register-hospital', [HospitalRegistrationController::class, 'register'])->name('hospital.register');
Route::get('/registration-success', [HospitalRegistrationController::class, 'success'])->name('hospital.registration.success');
Route::get('/check-slug', [HospitalRegistrationController::class, 'checkSlug'])->name('hospital.check-slug');



// Main application route
Route::get('/', function () {
    if (!file_exists(storage_path('installed'))) {
        return redirect('/install');
    }
    
    // Redirect based on user role
    if (auth()->check()) {
        return redirect()->to(app(\App\Http\Controllers\Auth\LoginController::class)->redirectTo(auth()->user()));
    }
    // if (auth()->check()) {
    //     if (auth()->user()->isSuperAdmin()) {
    //         return redirect()->route('super-admin.dashboard');
    //     } else {
    //         // Redirect to hospital dashboard
    //         return redirect()->route('hospital.dashboard');
    //     }
    // }
    
    return redirect()->route('login');
})->name('home');



Route::post('/logout', function () {
    auth()->logout();
    return redirect('/');
})->name('logout')->middleware('auth');

// Patient Self-Registration
Route::get('/register/{hospital}', [\App\Http\Controllers\PatientRegistrationController::class, 'show'])
    ->name('patient.register.show');

Route::post('/register/{hospital}', [\App\Http\Controllers\PatientRegistrationController::class, 'store'])
    ->name('patient.register.store');

// Patient Home (after login)
Route::middleware(['auth'])->group(function () {
    Route::get('/patient/home', function () {
        if (auth()->user()->role !== 'patient') {
            return redirect()->route('hospital.dashboard'); // admins go to hospital dashboard
        }
        return view('patient.home');
    })->name('patient.home');
});

// Doctor Dashboard
Route::middleware(['auth', \App\Http\Middleware\Doctor::class])
    ->prefix('doctor')
    ->name('doctor.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('doctor.dashboard');
        })->name('dashboard');

        Route::get('/appointments', function () {
            // You'll replace this with a real controller later
            return view('doctor.appointments.index');
        })->name('appointments.index');
    });

// Nurse Dashboard
Route::middleware(['auth', \App\Http\Middleware\Nurse::class])
    ->prefix('nurse')
    ->name('nurse.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('nurse.dashboard');
        })->name('dashboard');
    });

// Patient Dashboard
Route::middleware(['auth', 'patient'])
    ->prefix('patient')
    ->name('patient.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('patient.dashboard');
        })->name('dashboard');
    });

// Doctor Appointments
Route::middleware(['auth', \App\Http\Middleware\Doctor::class])
    ->prefix('doctor')
    ->name('doctor.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('doctor.dashboard');
        })->name('dashboard');

        Route::get('/appointments', [DoctorAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/calendar', [DoctorAppointmentController::class, 'calendar'])->name('appointments.calendar');
        
        // 👇 ADD THESE MISSING ROUTES
        Route::post('/appointments/{appointment}/confirm', [DoctorAppointmentController::class, 'confirm'])->name('appointments.confirm');
        Route::post('/appointments/{appointment}/complete', [DoctorAppointmentController::class, 'complete'])->name('appointments.complete');
    });

// Patient Appointments
Route::middleware(['auth', \App\Http\Middleware\Patient::class]) // ← AFTER creating middleware
    ->prefix('patient')
    ->name('patient.')
    ->group(function () {
        Route::get('/appointments', [PatientAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/book', [PatientAppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/appointments', [PatientAppointmentController::class, 'store'])->name('appointments.store');
        Route::post('/appointments/{appointment}/cancel', [PatientAppointmentController::class, 'cancel'])->name('appointments.cancel');
    });

// Nurse Appointments
Route::middleware(['auth', \App\Http\Middleware\Nurse::class])
    ->prefix('nurse')
    ->name('nurse.')
    ->group(function () {
        Route::get('/appointments', [NurseAppointmentController::class, 'index'])->name('appointments.index');
    });

    Route::middleware(['auth', \App\Http\Middleware\Doctor::class])
    ->prefix('doctor')
    ->name('doctor.')
    ->group(function () {
        Route::get('/calendar', [Doctor\CalendarController::class, 'index'])->name('calendar.index');
    });