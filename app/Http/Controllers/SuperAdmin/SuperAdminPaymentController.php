<?php
namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SuperAdminPaymentSettings;
use Illuminate\Http\Request;

class SuperAdminPaymentController extends Controller
{
    public function index()
    {
        $settings = SuperAdminPaymentSettings::all();
        return view('super-admin.super-admin-payment.index', compact('settings'));
    }

    public function edit($gateway)
    {
        $setting = SuperAdminPaymentSettings::where('payment_gateway', $gateway)->first();
        return view('super-admin.super-admin-payment.edit', compact('setting', 'gateway'));
    }

    public function update(Request $request, $gateway)
    {
        $request->validate([
            'public_key' => 'required|string',
            'secret_key' => 'required|string',
            'webhook_secret' => 'nullable|string',
        ]);

        SuperAdminPaymentSettings::updateOrCreate(
            ['payment_gateway' => $gateway],
            [
                'public_key' => $request->public_key,
                'secret_key' => $request->secret_key,
                'webhook_secret' => $request->webhook_secret,
                'is_active' => true,
            ]
        );

        return redirect()->route('super-admin.super-admin-payment.index')
            ->with('success', 'Super Admin payment settings updated!');
    }
}