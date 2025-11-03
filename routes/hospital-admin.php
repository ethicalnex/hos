<?php

use App\Http\Controllers\Hospital\DashboardController;
use App\Http\Controllers\Hospital\PatientController;
use App\Http\Controllers\Hospital\StaffController;
use App\Http\Controllers\Hospital\DepartmentController;
use App\Http\Controllers\Hospital\SettingsController;
use App\Http\Controllers\Hospital\SubscriptionController;
use App\Http\Controllers\Hospital\PharmacyController;
use App\Http\Controllers\Hospital\AppointmentController;
use App\Http\Controllers\Hospital\DoctorScheduleController;
use App\Http\Controllers\Hospital\ServiceController;
use App\Http\Controllers\Hospital\EmrController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Hospital\LabController;
use App\Http\Controllers\Hospital\AnalyticsController;

Route::middleware(['auth', \App\Http\Middleware\HospitalAdmin::class])
    ->prefix('hospital')
    ->name('hospital.')
    ->group(function () {
        
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
   
    // Patients
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');

    // Staff
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{user}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{user}', [StaffController::class, 'update'])->name('staff.update');
    Route::post('/staff/{user}/toggle', [StaffController::class, 'toggle'])->name('staff.toggle');
// Analytics & Reports
Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
Route::get('/analytics/reports', [AnalyticsController::class, 'reports'])->name('analytics.reports');
Route::post('/analytics/generate-report', [AnalyticsController::class, 'generateReport'])->name('analytics.generate-report');
    

    // EMR Management
    Route::prefix('emr')->name('emr.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Hospital\EmrController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Hospital\EmrController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Hospital\EmrController::class, 'store'])->name('store');
        Route::get('/{record}', [\App\Http\Controllers\Hospital\EmrController::class, 'show'])->name('show');
        Route::get('/{record}/edit', [\App\Http\Controllers\Hospital\EmrController::class, 'edit'])->name('edit');
        Route::put('/{record}', [\App\Http\Controllers\Hospital\EmrController::class, 'update'])->name('update');
    });
    
    // Departments
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store'); // ← ADDED
    Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
    Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
    
    // Services
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store'); // ← ADDED
    Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');

    // Subscription
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('/subscription', [SubscriptionController::class, 'subscribe'])->name('subscription.subscribe');
    Route::get('/subscription/callback', [SubscriptionController::class, 'callback'])->name('subscription.callback');
    Route::get('/subscription/renew/{renewal}', [SubscriptionController::class, 'renew'])->name('subscription.renew');
    Route::get('/subscription/renew/{renewal}/callback', [SubscriptionController::class, 'renewCallback'])->name('subscription.renew.callback');
    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel'); // ← POST only

    // Appointments
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');

    // Receptionist View
    Route::get('/receptionist/appointments', [\App\Http\Controllers\Receptionist\AppointmentController::class, 'index'])->name('receptionist.appointments.index');
    Route::post('/receptionist/appointments/{appointment}/confirm', [\App\Http\Controllers\Receptionist\AppointmentController::class, 'confirm'])->name('receptionist.appointments.confirm');

    // Doctor Schedules
    Route::get('/doctor-schedules', [DoctorScheduleController::class, 'index'])->name('doctor-schedules.index');
    Route::get('/doctor-schedules/create', [DoctorScheduleController::class, 'create'])->name('doctor-schedules.create');
    Route::post('/doctor-schedules', [DoctorScheduleController::class, 'store'])->name('doctor-schedules.store');
    Route::post('/doctor-schedules/{schedule}/toggle', [DoctorScheduleController::class, 'toggle'])->name('doctor-schedules.toggle');
    Route::delete('/doctor-schedules/{schedule}', [DoctorScheduleController::class, 'destroy'])->name('doctor-schedules.destroy');

    // Lab Management
    Route::prefix('lab')->name('lab.')->group(function () {
        Route::get('/categories', [LabController::class, 'categoriesIndex'])->name('categories.index');
        Route::get('/categories/create', [LabController::class, 'categoriesCreate'])->name('categories.create');
        Route::post('/categories', [LabController::class, 'categoriesStore'])->name('categories.store');
        Route::get('/categories/{category}/edit', [LabController::class, 'categoriesEdit'])->name('categories.edit');
        Route::put('/categories/{category}', [LabController::class, 'categoriesUpdate'])->name('categories.update');
        
        Route::get('/tests', [LabController::class, 'testsIndex'])->name('tests.index');
        Route::get('/tests/create', [LabController::class, 'testsCreate'])->name('tests.create');
        Route::post('/tests', [LabController::class, 'testsStore'])->name('tests.store');
        Route::get('/tests/{test}/edit', [LabController::class, 'testsEdit'])->name('tests.edit');
        Route::put('/tests/{test}', [LabController::class, 'testsUpdate'])->name('tests.update');
    });

     // Pharmacy Management
    Route::prefix('pharmacy')->name('pharmacy.')->group(function () {
        Route::get('/items', [PharmacyController::class, 'itemsIndex'])->name('items.index');
        Route::get('/items/create', [PharmacyController::class, 'itemsCreate'])->name('items.create');
        Route::post('/items', [PharmacyController::class, 'itemsStore'])->name('items.store');
        Route::get('/items/{item}/edit', [PharmacyController::class, 'itemsEdit'])->name('items.edit');
        Route::put('/items/{item}', [PharmacyController::class, 'itemsUpdate'])->name('items.update');
        
        Route::get('/inventory', [PharmacyController::class, 'inventoryIndex'])->name('inventory.index');
        Route::get('/inventory/{item}/add', [PharmacyController::class, 'inventoryAdd'])->name('inventory.add');
        Route::post('/inventory/{item}', [PharmacyController::class, 'inventoryStore'])->name('inventory.store');
    });
    
    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update'); // ← ADDED
    Route::get('/payment-settings', [SettingsController::class, 'paymentSettings'])->name('payment.settings');
    Route::post('/payment-settings', [SettingsController::class, 'savePaymentSettings'])->name('payment.save');
});