<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\HospitalPaymentSettings;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $hospitals = Hospital::with(['activePaymentSettings'])
            ->withCount(['activePaymentSettings'])
            ->latest()
            ->paginate(10);

        return view('super-admin.payments.index', compact('hospitals'));
    }

    public function show(Hospital $hospital)
    {
        $paymentSettings = $hospital->paymentSettings()->latest()->get();
        
        return view('super-admin.payments.show', compact('hospital', 'paymentSettings'));
    }
}