<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\AuthController;
use App\Http\Controllers\API\v1\PatientController;
use App\Http\Controllers\API\v1\AppointmentController;
use App\Http\Controllers\API\v1\EmrController;
use App\Http\Controllers\API\v1\LabOrderController;
use App\Http\Controllers\API\v1\PharmacyController;
use App\Http\Controllers\API\v1\SubscriptionController;
use App\Http\Controllers\API\v1\NotificationController;
use App\Http\Controllers\API\v1\SyncController;
use App\Http\Controllers\API\DocumentationController;

Route::prefix('v1')->group(function () {
    // Auth
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    // Patients
    Route::get('/patients', [PatientController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/patients/{id}', [PatientController::class, 'show'])->middleware('auth:sanctum');

    // Appointments
    Route::get('/appointments', [AppointmentController::class, 'index'])->middleware('auth:sanctum');
    Route::post('/appointments', [AppointmentController::class, 'store'])->middleware('auth:sanctum');
    Route::put('/appointments/{id}', [AppointmentController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy'])->middleware('auth:sanctum');

    // EMR
    Route::get('/emr', [EmrController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/emr/{id}', [EmrController::class, 'show'])->middleware('auth:sanctum');
    Route::post('/emr', [EmrController::class, 'store'])->middleware('auth:sanctum');
    Route::put('/emr/{id}', [EmrController::class, 'update'])->middleware('auth:sanctum');

    // Lab Orders
    Route::get('/lab-orders', [LabOrderController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/lab-orders/{id}', [LabOrderController::class, 'show'])->middleware('auth:sanctum');
    Route::post('/lab-orders', [LabOrderController::class, 'store'])->middleware('auth:sanctum');
    Route::put('/lab-orders/{id}', [LabOrderController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/lab-orders/{id}', [LabOrderController::class, 'destroy'])->middleware('auth:sanctum');

    // Pharmacy
    Route::get('/pharmacy/items', [PharmacyController::class, 'items'])->middleware('auth:sanctum');
    Route::get('/pharmacy/inventory', [PharmacyController::class, 'inventory'])->middleware('auth:sanctum');
    Route::get('/pharmacy/sales', [PharmacyController::class, 'sales'])->middleware('auth:sanctum');
    Route::post('/pharmacy/sales', [PharmacyController::class, 'createSale'])->middleware('auth:sanctum');
    Route::post('/pharmacy/dispense', [PharmacyController::class, 'dispensePrescription'])->middleware('auth:sanctum');

    // Subscription
    Route::get('/subscription', [SubscriptionController::class, 'plan'])->middleware('auth:sanctum');
    Route::post('/subscription', [SubscriptionController::class, 'subscribe'])->middleware('auth:sanctum');
    Route::post('/subscription/renew', [SubscriptionController::class, 'renew'])->middleware('auth:sanctum');

    // Push Notifications
    Route::post('/notifications/subscribe', [NotificationController::class, 'subscribe'])->middleware('auth:sanctum');
    Route::post('/notifications/send', [NotificationController::class, 'send'])->middleware('auth:sanctum');

    // Offline Sync
    Route::post('/sync/pull', [SyncController::class, 'pull'])->middleware('auth:sanctum');
    Route::post('/sync/push', [SyncController::class, 'push'])->middleware('auth:sanctum');

    // API Documentation (NEW)
    Route::get('/documentation', [DocumentationController::class, 'index'])->name('api.documentation');
    Route::post('/test', [DocumentationController::class, 'test'])->name('api.test');
});