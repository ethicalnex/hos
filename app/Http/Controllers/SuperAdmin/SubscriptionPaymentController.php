<?php
namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPayment;
use Illuminate\Http\Request;

class SubscriptionPaymentController extends Controller
{
    public function index()
    {
        $payments = SubscriptionPayment::with(['hospital', 'plan'])
            ->latest()
            ->paginate(20);
        return view('super-admin.subscription-payments.index', compact('payments'));
    }
}