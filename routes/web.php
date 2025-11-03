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
use App\Http\Controllers\Doctor\PrescriptionController;
use App\Http\Controllers\Pharmacy\DispenseController;
use App\Http\Controllers\Pharmacy\SaleController;
use App\Http\Controllers\Pharmacy\ReportController;
use App\Http\Controllers\Doctor\EmrController;
use App\Http\Controllers\Doctor\CalendarController;
// use App\Http\Controllers\Nurse\EmrController;

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
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/', function () {
    if (!file_exists(storage_path('installed'))) {
        return redirect('/install');
    }
    
    if (auth()->check()) {
        $loginController = app(\App\Http\Controllers\Auth\LoginController::class);
        return redirect()->to($loginController->redirectTo(auth()->user()));
    }
    
    return view('auth.login');
})->name('home');

// Public Routes
Route::get('/register/{hospital}', [\App\Http\Controllers\PatientRegistrationController::class, 'show'])->name('patient.register.show');
Route::post('/register/{hospital}', [\App\Http\Controllers\PatientRegistrationController::class, 'store'])->name('patient.register.store');
Route::get('/register-hospital', [HospitalRegistrationController::class, 'showRegistrationForm'])->name('hospital.registration');
Route::post('/register-hospital', [HospitalRegistrationController::class, 'register'])->name('hospital.register');
Route::get('/registration-success', [HospitalRegistrationController::class, 'success'])->name('hospital.registration.success');
Route::get('/register-hospital/callback', [HospitalRegistrationController::class, 'callback'])->name('hospital.registration.callback');

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

    // Pharmacy Routes
    Route::middleware([\App\Http\Middleware\Pharmacist::class])->prefix('pharmacy')->name('pharmacy.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Pharmacy\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dispense', [\App\Http\Controllers\Pharmacy\DispenseController::class, 'index'])->name('dispense.index');
        Route::get('/dispense/{prescription}', [\App\Http\Controllers\Pharmacy\DispenseController::class, 'show'])->name('dispense.show');
        Route::post('/dispense/{prescription}', [\App\Http\Controllers\Pharmacy\DispenseController::class, 'dispense'])->name('dispense.dispense');
        Route::get('/sales', [\App\Http\Controllers\Pharmacy\SaleController::class, 'index'])->name('sales.index');
        Route::get('/sales/create', [\App\Http\Controllers\Pharmacy\SaleController::class, 'create'])->name('sales.create');
        Route::post('/sales', [\App\Http\Controllers\Pharmacy\SaleController::class, 'store'])->name('sales.store');
        Route::get('/sales/{sale}', [\App\Http\Controllers\Pharmacy\SaleController::class, 'show'])->name('sales.show');
        Route::get('/reports', [\App\Http\Controllers\Pharmacy\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/generate', [\App\Http\Controllers\Pharmacy\ReportController::class, 'generate'])->name('reports.generate');
        Route::post('/reports', [\App\Http\Controllers\Pharmacy\ReportController::class, 'store'])->name('reports.store');
        Route::get('/reports/{report}/download', [\App\Http\Controllers\Pharmacy\ReportController::class, 'download'])->name('reports.download');
    });

    // Lab Technician Routes
    Route::middleware([\App\Http\Middleware\LabTechnician::class])->prefix('lab-technician')->name('lab-technician.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\LabTechnician\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/lab/results', [\App\Http\Controllers\LabTechnician\LabResultController::class, 'index'])->name('lab.results.index');
        Route::get('/lab/results/{order}/create', [\App\Http\Controllers\LabTechnician\LabResultController::class, 'create'])->name('lab.results.create');
        Route::post('/lab/results/{order}', [\App\Http\Controllers\LabTechnician\LabResultController::class, 'store'])->name('lab.results.store');
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
        Route::get('/lab/reports', [\App\Http\Controllers\Patient\LabReportController::class, 'index'])->name('lab.reports.index');
        Route::get('/lab/reports/{order}', [\App\Http\Controllers\Patient\LabReportController::class, 'show'])->name('lab.reports.show');
        Route::get('/lab/reports/{order}/download', [\App\Http\Controllers\Patient\LabReportController::class, 'download'])->name('lab.reports.download');
        Route::get('/appointments', [PatientAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/book', [PatientAppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/appointments', [PatientAppointmentController::class, 'store'])->name('appointments.store');
        Route::post('/appointments/{appointment}/cancel', [PatientAppointmentController::class, 'cancel'])->name('appointments.cancel');
        Route::get('/emr', [PatientEmrController::class, 'index'])->name('emr.index');
    });

    

    // Doctor Routes
    Route::middleware([\App\Http\Middleware\Doctor::class])->prefix('doctor')->name('doctor.')->group(function () {
        Route::get('/dashboard', function () {
            return view('doctor.dashboard');
        })->name('dashboard');
        Route::get('/lab/orders', [\App\Http\Controllers\Doctor\LabOrderController::class, 'index'])->name('lab.orders.index');
        Route::get('/lab/orders/create', [\App\Http\Controllers\Doctor\LabOrderController::class, 'create'])->name('lab.orders.create');
        Route::post('/lab/orders', [\App\Http\Controllers\Doctor\LabOrderController::class, 'store'])->name('lab.orders.store');
        Route::get('/appointments', [DoctorAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/calendar', [DoctorAppointmentController::class, 'calendar'])->name('appointments.calendar');
        Route::post('/appointments/{appointment}/confirm', [DoctorAppointmentController::class, 'confirm'])->name('appointments.confirm');
        Route::post('/appointments/{appointment}/complete', [DoctorAppointmentController::class, 'complete'])->name('appointments.complete');

        Route::get('/prescriptions', [\App\Http\Controllers\Doctor\PrescriptionController::class, 'index'])->name('prescriptions.index');
        Route::get('/prescriptions/create', [\App\Http\Controllers\Doctor\PrescriptionController::class, 'create'])->name('prescriptions.create');
        Route::post('/prescriptions', [\App\Http\Controllers\Doctor\PrescriptionController::class, 'store'])->name('prescriptions.store');

        //EMR
        // Doctor EMR
    Route::prefix('emr')->name('emr.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Doctor\EmrController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Doctor\EmrController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Doctor\EmrController::class, 'store'])->name('store');
        Route::get('/{record}', [\App\Http\Controllers\Doctor\EmrController::class, 'show'])->name('show');
        Route::get('/{record}/edit', [\App\Http\Controllers\Doctor\EmrController::class, 'edit'])->name('edit');
        Route::put('/{record}', [\App\Http\Controllers\Doctor\EmrController::class, 'update'])->name('update');
});
    });


    // Nurse Routes
    Route::middleware([\App\Http\Middleware\Nurse::class])->prefix('nurse')->name('nurse.')->group(function () {
        Route::get('/dashboard', function () {
            return view('nurse.dashboard');
        })->name('dashboard');
        Route::get('/appointments', [NurseAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/emr/{record}/notes', [\App\Http\Controllers\Nurse\EmrController::class, 'editNotes'])->name('emr.notes.edit');
        Route::put('/emr/{record}/notes', [\App\Http\Controllers\Nurse\EmrController::class, 'updateNotes'])->name('emr.notes.update');
    });
});

Route::get('/api/documentation', [\App\Http\Controllers\API\DocumentationController::class, 'index'])->name('api.documentation');
Route::post('/api/test', [\App\Http\Controllers\API\DocumentationController::class, 'test'])->name('api.test');

Route::get('/offline.html', function () {
    return view('offline');
})->name('offline');

// Include external route files LAST
require __DIR__.'/auth.php';
require __DIR__.'/hospital-admin.php';
require __DIR__.'/super-admin.php';