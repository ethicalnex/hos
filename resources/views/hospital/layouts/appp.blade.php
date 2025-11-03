IF YOU FIND THE ERRO, WRITE THE COMPLETE UPDATED FILE(S)

1. app/Http/Middleware/CheckFeatureAccess.php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFeatureAccess
{
    public function handle(Request $request, Closure $next, $feature)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $hospital = auth()->user()->hospital;

        // If no hospital assigned, deny access
        if (!$hospital) {
            abort(403, 'No hospital assigned to user');
        }

        // Check if feature is available in current plan
        if (!$hospital->subscriptionPlan || !$hospital->subscriptionPlan->hasFeature($feature)) {
            return redirect()->route('hospital.subscription.index')
                ->with('upgrade_required', "This feature ($feature) requires a higher plan. Please upgrade your subscription.");
        }

        // Check if trial has expired
        if ($hospital->isTrialActive() && $hospital->trial_ends_at->isPast()) {
            return redirect()->route('hospital.subscription.index')
                ->with('trial_expired', 'Your free trial has expired. Please upgrade to continue using premium features.');
        }

        return $next($request);
    }
}

2. app/Http/Kernel.php
<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     */
    protected $middleware = [
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's middleware groups.
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\IdentifyTenant::class,
        ],

        'api' => [
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     */
    protected $routeMiddleware = [
        // Laravel default route middleware
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        // Custom Middleware
        'super_admin' => \App\Http\Middleware\SuperAdmin::class,
        'hospital_admin' => \App\Http\Middleware\HospitalAdmin::class,
        'doctor' => \App\Http\Middleware\Doctor::class,
        'nurse' => \App\Http\Middleware\Nurse::class,
        'lab_technician' => \App\Http\Middleware\LabTechnician::class,
        'pharmacist' => \App\Http\Middleware\Pharmacist::class,
        'receptionist' => \App\Http\Middleware\Receptionist::class,
        'patient' => \App\Http\Middleware\Patient::class,
        
        // Feature Access Middleware
        'feature.access' => \App\Http\Middleware\CheckFeatureAccess::class,
    ];

    /**
     * The application's middleware aliases.
     */
    protected $middlewareAliases = [
        // Laravel default aliases
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        // Custom Middleware Aliases
        'super_admin' => \App\Http\Middleware\SuperAdmin::class,
        'hospital_admin' => \App\Http\Middleware\HospitalAdmin::class,
        'doctor' => \App\Http\Middleware\Doctor::class,
        'nurse' => \App\Http\Middleware\Nurse::class,
        'lab_technician' => \App\Http\Middleware\LabTechnician::class,
        'pharmacist' => \App\Http\Middleware\Pharmacist::class,
        'receptionist' => \App\Http\Middleware\Receptionist::class,
        'patient' => \App\Http\Middleware\Patient::class,
        
        // Feature Access Middleware Alias
        'feature.access' => \App\Http\Middleware\CheckFeatureAccess::class,
    ];
}

3. routes/hospital-admin.php
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
use App\Http\Middleware\CheckFeatureAccess;

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

    // EMR Management
    Route::prefix('emr')->name('emr.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Hospital\EmrController::class, 'index'])->name('index')->middleware('feature.access:emr');
        Route::get('/create', [\App\Http\Controllers\Hospital\EmrController::class, 'create'])->name('create')->middleware('feature.access:emr');
        Route::post('/', [\App\Http\Controllers\Hospital\EmrController::class, 'store'])->name('store')->middleware('feature.access:emr');
        Route::get('/{record}', [\App\Http\Controllers\Hospital\EmrController::class, 'show'])->name('show')->middleware('feature.access:emr');
        Route::get('/{record}/edit', [\App\Http\Controllers\Hospital\EmrController::class, 'edit'])->name('edit')->middleware('feature.access:emr');
        Route::put('/{record}', [\App\Http\Controllers\Hospital\EmrController::class, 'update'])->name('update')->middleware('feature.access:emr');
    });
    
    // Departments
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
    Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
    
    // Services (MOVED INSIDE THE GROUP!)
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');

    // Add to hospital admin group
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('/subscription', [SubscriptionController::class, 'subscribe'])->name('subscription.subscribe');
    Route::get('/subscription/callback', [SubscriptionController::class, 'callback'])->name('subscription.callback');
    // Subscription Renewal & Expiry Handling
    Route::get('/subscription/renew/{renewal}', [SubscriptionController::class, 'renew'])->name('subscription.renew');
    Route::get('/subscription/renew/{renewal}/callback', [SubscriptionController::class, 'renewCallback'])->name('subscription.renew.callback');
    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');

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
        Route::get('/categories', [LabController::class, 'categoriesIndex'])->name('categories.index')->middleware('feature.access:lab');
        Route::get('/categories/create', [LabController::class, 'categoriesCreate'])->name('categories.create')->middleware('feature.access:lab');
        Route::post('/categories', [LabController::class, 'categoriesStore'])->name('categories.store')->middleware('feature.access:lab');
        Route::get('/categories/{category}/edit', [LabController::class, 'categoriesEdit'])->name('categories.edit')->middleware('feature.access:lab');
        Route::put('/categories/{category}', [LabController::class, 'categoriesUpdate'])->name('categories.update')->middleware('feature.access:lab');
        
        Route::get('/tests', [LabController::class, 'testsIndex'])->name('tests.index')->middleware('feature.access:lab');
        Route::get('/tests/create', [LabController::class, 'testsCreate'])->name('tests.create')->middleware('feature.access:lab');
        Route::post('/tests', [LabController::class, 'testsStore'])->name('tests.store')->middleware('feature.access:lab');
        Route::get('/tests/{test}/edit', [LabController::class, 'testsEdit'])->name('tests.edit')->middleware('feature.access:lab');
        Route::put('/tests/{test}', [LabController::class, 'testsUpdate'])->name('tests.update')->middleware('feature.access:lab');
    });

    // Pharmacy Management
    Route::prefix('pharmacy')->name('pharmacy.')->group(function () {
        Route::get('/items', [PharmacyController::class, 'itemsIndex'])->name('items.index')->middleware('feature.access:pharmacy');
        Route::get('/items/create', [PharmacyController::class, 'itemsCreate'])->name('items.create')->middleware('feature.access:pharmacy');
        Route::post('/items', [PharmacyController::class, 'itemsStore'])->name('items.store')->middleware('feature.access:pharmacy');
        Route::get('/items/{item}/edit', [PharmacyController::class, 'itemsEdit'])->name('items.edit')->middleware('feature.access:pharmacy');
        Route::put('/items/{item}', [PharmacyController::class, 'itemsUpdate'])->name('items.update')->middleware('feature.access:pharmacy');
        
        Route::get('/inventory', [PharmacyController::class, 'inventoryIndex'])->name('inventory.index')->middleware('feature.access:pharmacy');
        Route::get('/inventory/{item}/add', [PharmacyController::class, 'inventoryAdd'])->name('inventory.add')->middleware('feature.access:pharmacy');
        Route::post('/inventory/{item}', [PharmacyController::class, 'inventoryStore'])->name('inventory.store')->middleware('feature.access:pharmacy');
    });
    
    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/payment-settings', [SettingsController::class, 'paymentSettings'])->name('payment.settings');
    Route::post('/payment-settings', [SettingsController::class, 'savePaymentSettings'])->name('payment.save');
});


4. app/Models/Hospital.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'logo',
        'is_active',
        'subscription_ends_at',
         'subscription_plan_id',    // ← ADD THIS
    'is_trial_active',        // ← ADD THIS  
    'trial_ends_at',          // ← ADD THIS
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscription_ends_at' => 'datetime',
        'trial_ends_at' => 'date', 
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function paymentSettings()
    {
        return $this->hasMany(HospitalPaymentSettings::class);
    }

    public function activePaymentSettings()
    {
        return $this->hasMany(HospitalPaymentSettings::class)->where('is_active', true);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // app/Models/Hospital.php
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Helpers
    public function isSubscribed()
    {
        return $this->subscription_ends_at && $this->subscription_ends_at->isFuture();
    }

    public function getActivePaymentGateway($gateway)
    {
        return $this->activePaymentSettings()->where('payment_gateway', $gateway)->first();
    }
    // Add these relationships
    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(HospitalSubscription::class);
    }
    public function hasFeature($feature)
    {
        return $this->subscriptionPlan && $this->subscriptionPlan->hasFeature($feature);
    }

    public function isTrialActive()
    {
        return $this->is_trial_active && $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function hasActiveSubscription()
    {
        return $this->currentSubscription() && $this->currentSubscription()->ends_at->isFuture();
    }

    public function currentSubscription()
    {
        return $this->subscriptions()->where('is_active', true)->latest()->first();
    }

    public function getFeatureName($feature)
    {
        return $this->subscriptionPlan ? $this->subscriptionPlan->getFeatureName($feature) : $feature;
    }
    

        // app/Models/Hospital.php
    public function departments()
    {
        return $this->hasMany(\App\Models\Department::class);
    }
}

5. app/Models/SubscriptionPlan.php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'currency', 'trial_days', 'billing_cycle', 
        'is_active', 'features', 'max_staff', 'max_patients', 'max_departments'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'features' => 'array',
    ];

    public function hospitals()
    {
        return $this->hasMany(Hospital::class);
    }

    public function hasFeature($feature)
    {
        return $this->features[$feature] ?? false;
    }

    public function getFeatureName($feature)
    {
        $features = [
            'emr' => 'EMR',
            'lab' => 'Lab Management',
            'pharmacy' => 'Pharmacy',
            'billing' => 'Billing & Invoicing',
            'appointments' => 'Appointment Booking',
            'reports' => 'Reports & Analytics',
            'ai' => 'AI Diagnostics',
            'mobile_app' => 'Mobile App Access',
            'sms' => 'SMS Reminders',
            'api' => 'API Integration',
        ];
        return $features[$feature] ?? $feature;
    }
}


6. resources/views/hospital/layouts/app.blade.php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hospital Dashboard')</title>

    <!-- Google Fonts: Inter (clean, modern) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="  https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css  " rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css  " rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        /* Sidebar */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1e3a8a, #1e40af);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            padding: 0;
        }

        .hospital-brand {
            padding: 1.25rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background: rgba(0,0,0,0.1);
        }

        .hospital-brand h5 {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.85);
            padding: 0.85rem 1.25rem;
            margin: 0.15rem 0.75rem;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.25s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
            transform: translateX(4px);
        }

        .sidebar .nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 12px;
            font-size: 1.1rem;
        }

        /* Main content area */
        main {
            margin-left: 16.666667%; /* col-lg-2 = ~16.66% */
            padding-top: 20px;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                min-height: auto;
            }
            main {
                margin-left: 0;
            }
        }

        /* Top navbar */
        .top-navbar {
            background: white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            padding: 0.6rem 1.25rem;
        }

        .top-navbar .dropdown-toggle::after {
            margin-left: 0.5rem;
        }

        /* Stat cards (if used in content) */
        .stat-card {
            border-left: 4px solid #3b82f6;
            transition: transform 0.25s, box-shadow 0.25s;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
    </style>
</head>
<!-- Feature Restriction Modal -->
@if(session('upgrade_required'))
<div class="modal fade" id="upgradeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Feature Upgrade Required</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ session('upgrade_required') }}</p>
            </div>
            <div class="modal-footer">
                <a href="{{ route('hospital.subscription.index') }}" class="btn btn-primary">
                    <i class="fas fa-crown me-1"></i> Upgrade Plan
                </a>
            </div>
        </div>
    </div>
</div>
@endif

@if(session('trial_expired'))
<div class="modal fade" id="trialExpiredModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Free Trial Expired</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ session('trial_expired') }}</p>
            </div>
            <div class="modal-footer">
                <a href="{{ route('hospital.subscription.index') }}" class="btn btn-primary">
                    <i class="fas fa-crown me-1"></i> Upgrade Now
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(session('upgrade_required'))
        var upgradeModal = new bootstrap.Modal(document.getElementById('upgradeModal'));
        upgradeModal.show();
    @endif
    
    @if(session('trial_expired'))
        var trialExpiredModal = new bootstrap.Modal(document.getElementById('trialExpiredModal'));
        trialExpiredModal.show();
    @endif
});
</script>
<body>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->

            <nav class="col-lg-2 col-md-3 d-md-block sidebar collapse">
                <div class="hospital-brand">
                    <h5>
                        <i class="fas fa-hospital me-2"></i>
                        {{ auth()->user()->hospital->name }}
                    </h5>
                    <small class="text-light opacity-75">Hospital Admin Panel</small>
                </div>

                <div class="position-sticky pt-3">
                    <ul class="nav flex-column px-2">
                        <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('hospital.dashboard') ? 'active' : '' }}" 
       href="{{ route('hospital.dashboard') }}">
        <i class="fas fa-home me-2"></i>Dashboard
    </a>
</li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('hospital.staff.*') ? 'active' : '' }}" href="{{ route('hospital.staff.index') }}">
                                <i class="fas fa-users"></i> Staff Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('hospital.doctor-schedules.*') ? 'active' : '' }}" href="{{ route('hospital.doctor-schedules.index') }}">
                                <i class="fas fa-clock"></i> Doctor Schedules
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('hospital.patients.*') ? 'active' : '' }}" href="{{ route('hospital.patients.index') }}">
                                <i class="fas fa-procedures"></i> Patients
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('hospital.departments.*') ? 'active' : '' }}" href="{{ route('hospital.departments.index') }}">
                                <i class="fas fa-building"></i> Departments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('hospital.services.*') ? 'active' : '' }}" href="{{ route('hospital.services.index') }}">
                                <i class="fas fa-concierge-bell"></i> Services
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('hospital.appointments.*') ? 'active' : '' }}" href="{{ route('hospital.appointments.index') }}">
                                <i class="fas fa-calendar-check"></i> Appointments
                            </a>
                        </li>
                       <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('hospital.lab.*') ? 'active' : '' }} 
                           {{ !auth()->user()->hospital->hasFeature('lab') ? 'disabled' : '' }}"
                           href="{{ auth()->user()->hospital->hasFeature('lab') ? route('hospital.lab.categories.index') : '#' }}">
                            <i class="fas fa-flask me-2"></i>Lab Management
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('hospital.pharmacy.*') ? 'active' : '' }} 
                           {{ !auth()->user()->hospital->hasFeature('pharmacy') ? 'disabled' : '' }}"
                           href="{{ auth()->user()->hospital->hasFeature('pharmacy') ? route('hospital.pharmacy.items.index') : '#' }}">
                            <i class="fas fa-pills me-2"></i>Pharmacy Management
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('hospital.emr.*') ? 'active' : '' }} 
                           {{ !auth()->user()->hospital->hasFeature('emr') ? 'disabled' : '' }}"
                           href="{{ auth()->user()->hospital->hasFeature('emr') ? route('hospital.emr.index') : '#' }}">
                            <i class="fas fa-file-medical me-2"></i>EMR
                        </a>
                    </li>           <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('hospital.receptionist.*') ? 'active' : '' }}" href="{{ route('hospital.receptionist.appointments.index') }}">
                                <i class="fas fa-user-md"></i> Receptionist View
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('hospital.subscription.*') ? 'active' : '' }}" href="{{ route('hospital.subscription.index') }}">
                                <i class="fas fa-crown"></i> Subscription
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('hospital.settings.*') ? 'active' : '' }}" href="{{ route('hospital.settings.index') }}">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                        </li>
                        <li class="nav-item mt-4">
                            <a class="nav-link text-warning" href="{{ route('super-admin.dashboard') }}">
                                <i class="fas fa-arrow-left me-1"></i> Super Admin
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Top Navbar -->
                <nav class="navbar top-navbar navbar-expand">
                    <div class="ms-auto">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-md me-2"></i>
                                    {{ auth()->user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.show') }}">
                                            <i class="fas fa-user me-2"></i> My Profile
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>

                <!-- Page Content -->
                <div class="container-fluid py-4">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js  "></script>
    @stack('scripts')
</body>

</html>

7 & 8, THIS IS NOT A PHARMACY ERROR, I SEE IT ON EMR AND  AND LAB MANAGEMENT TOO