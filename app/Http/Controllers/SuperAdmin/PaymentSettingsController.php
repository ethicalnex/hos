<?php
namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use Illuminate\Http\Request;

class PaymentSettingsController extends Controller
{
    public function index()
    {
        $hospitals = Hospital::with('paymentSettings')->get();
        return view('super-admin.payment-settings.index', compact('hospitals'));
    }

    public function edit(Hospital $hospital)
    {
        $settings = $hospital->paymentSettings;
        return view('super-admin.payment-settings.edit', compact('hospital', 'settings'));
    }

    public function update(Request $request, Hospital $hospital)
    {
        $request->validate([
            'payment_gateway' => 'required|in:paystack,flutterwave',
            'public_key' => 'required|string',
            'secret_key' => 'required|string',
            'webhook_secret' => 'nullable|string',
        ]);

        // Update or create payment settings
        $hospital->paymentSettings()->updateOrCreate(
            ['payment_gateway' => $request->payment_gateway],
            [
                'public_key' => $request->public_key,
                'secret_key' => $request->secret_key,
                'webhook_secret' => $request->webhook_secret,
                'is_active' => true,
            ]
        );

        return redirect()->route('super-admin.payment-settings.index')
            ->with('success', 'Payment settings updated successfully!');
    }
}