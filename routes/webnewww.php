<?php

use App\Http\Controllers\InstallController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Doctor\AppointmentController as DoctorAppointmentController;
use App\Http\Controllers\Patient\AppointmentController as PatientAppointmentController;
use App\Http\Controllers\Nurse\AppointmentController as NurseAppointmentController;
use App\Http\Controllers\Booking\BookingController;
use App\Http\Controllers\HospitalRegistrationController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\PatientEmrController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Doctor\LabOrderController;
use App\Http\Controllers\LabTechnician\LabResultController;
use App\Http\Controllers\Patient\LabReportController;
use App\Http\Controllers\LabTechnician\DashboardController;
use App\Http\Controllers\LabTechnician\AppointmentController;

// Installer Routes
Route::get('/install', [InstallController::class, 'welcome'])->name('install.welcome');
Route::get('/install/requirements', [InstallController::class, 'requirements'])->name('install.requirements');
Route::get('/install/permissions', [InstallController::class, 'permissions'])->name('install.permissions');
Route::get('/install/database', [InstallController::class, 'database'])->name('install.database');
Route::post('/install/test-database', [InstallController::class, 'testDatabase'])->name('install.testDatabase');
Route::post('/install/save-database', [InstallController::class, 'saveDatabase'])->name('install.saveDatabase');
Route::post('/install/process', [InstallController::class, 'install'])->name('install.process');
Route::get('/install/complete', [InstallController::class, 'complete'])->name('install.complete');





// Auth Routes
// Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [LoginController::class, 'login']);
// Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
// Replace your current login routes with these:
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/', function () {
    if (!file_exists(storage_path('installed'))) {
        return redirect('/install');
    }
    
    if (auth()->check()) {
        // Use the redirectTo method properly
        $loginController = app(\App\Http\Controllers\Auth\LoginController::class);
        return redirect()->to($loginController->redirectTo(auth()->user()));
    }
    
    return view('auth.login');
})->name('home');
// Public Routes (no auth)
// Route::get('/', function () {
//     if (!file_exists(storage_path('installed'))) {
//         return redirect('/install');
//     }
    
//     // If already authenticated, redirect to dashboard
//     if (auth()->check()) {
//         return redirect()->to(app(\App\Http\Controllers\Auth\LoginController::class)->redirectTo(auth()->user()));
//     }
    
//     // If not authenticated, show login form directly (NO redirect)
//     return view('auth.login');
// })->name('home');

// Patient Self-Registration
Route::get('/register/{hospital}', [\App\Http\Controllers\PatientRegistrationController::class, 'show'])->name('patient.register.show');
Route::post('/register/{hospital}', [\App\Http\Controllers\PatientRegistrationController::class, 'store'])->name('patient.register.store');
// Hospital Registration
Route::get('/register-hospital', [HospitalRegistrationController::class, 'showRegistrationForm'])->name('hospital.registration');
Route::post('/register-hospital', [HospitalRegistrationController::class, 'register'])->name('hospital.register');
Route::get('/registration-success', [HospitalRegistrationController::class, 'success'])->name('hospital.registration.success');
Route::get('/register-hospital/callback', [HospitalRegistrationController::class, 'callback'])->name('hospital.registration.callback');
// // Hospital Registration
// Route::get('/register-hospital', [HospitalRegistrationController::class, 'showRegistrationForm'])->name('hospital.registration');
// Route::post('/register-hospital', [HospitalRegistrationController::class, 'register'])->name('hospital.register');
// Route::get('/registration-success', [HospitalRegistrationController::class, 'success'])->name('hospital.registration.success');
// Route::get('/check-slug', [HospitalRegistrationController::class, 'checkSlug'])->name('hospital.check-slug');

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

// Public Webhooks
Route::post('/webhooks/paystack', [WebhookController::class, 'handlePaystack']);
Route::post('/webhooks/flutterwave', [WebhookController::class, 'handleFlutterwave']);

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('update');
        Route::put('/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('password');
    });

    // Lab Technician Routes - CORRECTED
  Route::middleware([\App\Http\Middleware\LabTechnician::class])->prefix('lab-technician')->name('lab-technician.')->group(function () { 
        Route::get('/dashboard', [\App\Http\Controllers\LabTechnician\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/lab/results', [\App\Http\Controllers\LabTechnician\LabResultController::class, 'index'])->name('lab.results.index');
        Route::get('/lab/results/{order}/create', [\App\Http\Controllers\LabTechnician\LabResultController::class, 'create'])->name('lab.results.create');
        Route::post('/lab/results/{order}', [\App\Http\Controllers\LabTechnician\LabResultController::class, 'store'])->name('lab.results.store');
    });




            Route::get('/appointments', [\App\Http\Controllers\LabTechnician\AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/{appointment}', [\App\Http\Controllers\LabTechnician\AppointmentController::class, 'show'])->name('appointments.show');



    });

    // Patient Routes
  Route::middleware(['auth', \App\Http\Middleware\Patient::class])->prefix('patient')->name('patient.')->group(function () {
        Route::get('/dashboard', function () {
            return view('patient.dashboard');
        })->name('dashboard');
        
        Route::get('/home', function () {
            return view('patient.home');
        })->name('home');
        
        Route::get('/profile', function () {
            return view('patient.profile', ['patient' => auth()->user()->patient]);
        })->name('profile');

        Route::get('/prescriptions', [\App\Http\Controllers\Patient\PrescriptionController::class, 'index'])->name('prescriptions.index');
    Route::get('/prescriptions/{prescription}', [\App\Http\Controllers\Patient\PrescriptionController::class, 'show'])->name('prescriptions.show');


        // Lab Reports
    Route::get('/lab/reports', [\App\Http\Controllers\Patient\LabReportController::class, 'index'])->name('lab.reports.index');
    Route::get('/lab/reports/{order}', [\App\Http\Controllers\Patient\LabReportController::class, 'show'])->name('lab.reports.show');
    Route::get('/lab/reports/{order}/download', [\App\Http\Controllers\Patient\LabReportController::class, 'download'])->name('lab.reports.download');

        
        // Appointments
        Route::get('/appointments', [PatientAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/book', [PatientAppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/appointments', [PatientAppointmentController::class, 'store'])->name('appointments.store');
        Route::post('/appointments/{appointment}/cancel', [PatientAppointmentController::class, 'cancel'])->name('appointments.cancel');
        
        // EMR
        Route::get('/emr', [PatientEmrController::class, 'index'])->name('emr.index');
    });
// Pharmacy Dispensing
Route::middleware([\App\Http\Middleware\Pharmacist::class])->prefix('pharmacy')->name('pharmacy.')->group(function () {
    Route::get('/dispense', [\App\Http\Controllers\Pharmacy\DispenseController::class, 'index'])->name('dispense.index');
    Route::get('/dispense/{prescription}', [\App\Http\Controllers\Pharmacy\DispenseController::class, 'show'])->name('dispense.show');
    Route::post('/dispense/{prescription}', [\App\Http\Controllers\Pharmacy\DispenseController::class, 'dispense'])->name('dispense.dispense');
});

// Pharmacy Dashboard
Route::middleware([\App\Http\Middleware\Pharmacist::class])->prefix('pharmacy')->name('pharmacy.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Pharmacy\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/sales', [\App\Http\Controllers\Pharmacy\SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [\App\Http\Controllers\Pharmacy\SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [\App\Http\Controllers\Pharmacy\SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{sale}', [\App\Http\Controllers\Pharmacy\SaleController::class, 'show'])->name('sales.show');
    Route::get('/reports', [\App\Http\Controllers\Pharmacy\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/generate', [\App\Http\Controllers\Pharmacy\ReportController::class, 'generate'])->name('reports.generate');
    Route::post('/reports', [\App\Http\Controllers\Pharmacy\ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{report}/download', [\App\Http\Controllers\Pharmacy\ReportController::class, 'download'])->name('reports.download');
});

        
// Doctor Lab Orders
Route::middleware([\App\Http\Middleware\Doctor::class])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', function () {
            return view('doctor.dashboard');
        })->name('dashboard');
    Route::get('/lab/orders', [\App\Http\Controllers\Doctor\LabOrderController::class, 'index'])->name('lab.orders.index');
    Route::get('/lab/orders/create', [\App\Http\Controllers\Doctor\LabOrderController::class, 'create'])->name('lab.orders.create');
    Route::post('/lab/orders', [\App\Http\Controllers\Doctor\LabOrderController::class, 'store'])->name('lab.orders.store');
});
        
        // Appointments
        Route::get('/appointments', [DoctorAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/calendar', [DoctorAppointmentController::class, 'calendar'])->name('appointments.calendar');
        Route::post('/appointments/{appointment}/confirm', [DoctorAppointmentController::class, 'confirm'])->name('appointments.confirm');
        Route::post('/appointments/{appointment}/complete', [DoctorAppointmentController::class, 'complete'])->name('appointments.complete');


        Route::get('/prescriptions', [\App\Http\Controllers\Doctor\PrescriptionController::class, 'index'])->name('prescriptions.index');
    Route::get('/prescriptions/create', [\App\Http\Controllers\Doctor\PrescriptionController::class, 'create'])->name('prescriptions.create');
    Route::post('/prescriptions', [\App\Http\Controllers\Doctor\PrescriptionController::class, 'store'])->name('prescriptions.store');
        
        // EMR
     Route::get('/emr/create/{appointment}', [\App\Http\Controllers\Doctor\EmrController::class, 'create'])->name('emr.create');
        Route::post('/emr', [\App\Http\Controllers\Doctor\EmrController::class, 'store'])->name('emr.store');
        Route::get('/emr/{record}', [\App\Http\Controllers\Doctor\EmrController::class, 'show'])->name('emr.show');
        Route::get('/emr/{record}/edit', [\App\Http\Controllers\Doctor\EmrController::class, 'edit'])->name('emr.edit');
        Route::put('/emr/{record}', [\App\Http\Controllers\Doctor\EmrController::class, 'update'])->name('emr.update');
        
        // Calendar
        Route::get('/calendar', [\App\Http\Controllers\Doctor\CalendarController::class, 'index'])->name('calendar.index');

    // Nurse Routes
    Route::middleware([\App\Http\Middleware\Nurse::class])->prefix('nurse')->name('nurse.')->group(function () {
        Route::get('/dashboard', function () {
            return view('nurse.dashboard');
        })->name('dashboard');
        
        Route::get('/appointments', [NurseAppointmentController::class, 'index'])->name('appointments.index');
        
        // EMR
        Route::get('/emr/{record}/notes', [\App\Http\Controllers\Nurse\EmrController::class, 'editNotes'])->name('emr.notes.edit');
        Route::put('/emr/{record}/notes', [\App\Http\Controllers\Nurse\EmrController::class, 'updateNotes'])->name('emr.notes.update');
   });

// Include external route files LAST
require __DIR__.'/auth.php';
require __DIR__.'/hospital-admin.php';
require __DIR__.'/super-admin.php';


