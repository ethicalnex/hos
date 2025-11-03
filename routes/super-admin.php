<?php

use App\Http\Controllers\SuperAdminController;
// use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\PaymentController;
use App\Http\Controllers\SuperAdmin\AnalyticsController;
use App\Http\Controllers\SuperAdmin\SuperAdminPaymentController;
use App\Http\Controllers\SuperAdmin\SubscriptionPaymentController;
use App\Http\Controllers\SuperAdmin\SubscriptionPlanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\PaymentSettingsController;

Route::middleware(['auth', \App\Http\Middleware\SuperAdmin::class])
    ->prefix('super-admin')
    ->name('super-admin.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');

    // Hospitals Management
    Route::get('/hospitals', [SuperAdminController::class, 'hospitals'])->name('hospitals.index');
    Route::get('/hospitals/create', [SuperAdminController::class, 'createHospital'])->name('hospitals.create');
    Route::post('/hospitals', [SuperAdminController::class, 'storeHospital'])->name('hospitals.store');
    Route::get('/hospitals/{hospital}/edit', [SuperAdminController::class, 'editHospital'])->name('hospitals.edit');
    Route::put('/hospitals/{hospital}', [SuperAdminController::class, 'updateHospital'])->name('hospitals.update');
    Route::post('/hospitals/{hospital}/toggle', [SuperAdminController::class, 'toggleHospital'])->name('hospitals.toggle');

    // Users Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::post('/users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');

    // Payment Settings
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{hospital}', [PaymentController::class, 'show'])->name('payments.show');

    // Super Admin Payment Settings
    Route::get('/super-admin-payment', [SuperAdminPaymentController::class, 'index'])->name('super-admin-payment.index');
    Route::get('/super-admin-payment/{gateway}/edit', [SuperAdminPaymentController::class, 'edit'])->name('super-admin-payment.edit');
    Route::put('/super-admin-payment/{gateway}', [SuperAdminPaymentController::class, 'update'])->name('super-admin-payment.update');

    // Subscription Payments
    Route::get('/subscription-payments', [SubscriptionPaymentController::class, 'index'])->name('subscription-payments.index');

    // Analytics & Reports
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/reports', [AnalyticsController::class, 'reports'])->name('reports');
    Route::post('/reports/generate', [AnalyticsController::class, 'generateReport'])->name('reports.generate');

    // Payment Settings for Hospitals
    Route::get('/payment-settings', [PaymentSettingsController::class, 'index'])->name('payment-settings.index');
    Route::get('/payment-settings/{hospital}/edit', [PaymentSettingsController::class, 'edit'])->name('payment-settings.edit');
    Route::put('/payment-settings/{hospital}', [PaymentSettingsController::class, 'update'])->name('payment-settings.update');

    // Subscription Plans (EXPLICIT ROUTES)
    Route::prefix('subscription-plans')->name('subscription-plans.')->group(function () {
        Route::get('/', [SubscriptionPlanController::class, 'index'])->name('index');
        Route::get('/create', [SubscriptionPlanController::class, 'create'])->name('create');
        Route::post('/', [SubscriptionPlanController::class, 'store'])->name('store');
        Route::get('/{subscription_plan}/edit', [SubscriptionPlanController::class, 'edit'])->name('edit');
        Route::put('/{subscription_plan}', [SubscriptionPlanController::class, 'update'])->name('update');
        Route::delete('/{subscription_plan}', [SubscriptionPlanController::class, 'destroy'])->name('destroy');
    });
});