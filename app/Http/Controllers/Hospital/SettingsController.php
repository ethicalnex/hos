<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\HospitalPaymentSettings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $hospital = auth()->user()->hospital;
        return view('hospital.settings.index', compact('hospital'));
    }

    public function update(Request $request)
    {
        $hospital = auth()->user()->hospital;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:hospitals,email,' . $hospital->id,
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'address', 'city', 'state']);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($hospital->logo) {
                \Storage::delete('public/hospital-logos/' . $hospital->logo);
            }

            $logoName = $hospital->id . '_logo.' . $request->logo->getClientOriginalExtension();
            $request->logo->storeAs('public/hospital-logos', $logoName);
            $data['logo'] = $logoName;
        }

        $hospital->update($data);

        return redirect()->route('hospital.settings.index')
            ->with('success', 'Hospital settings updated successfully!');
    }

    public function paymentSettings()
    {
        $hospital = auth()->user()->hospital;
        $paymentSettings = $hospital->paymentSettings;

        return view('hospital.settings.payment', compact('hospital', 'paymentSettings'));
    }

    public function savePaymentSettings(Request $request)
    {
        $hospital = auth()->user()->hospital;

        $request->validate([
            'payment_gateway' => 'required|in:paystack,flutterwave,stripe',
            'public_key' => 'required|string',
            'secret_key' => 'required|string',
        ]);

        // Deactivate other payment settings for this gateway
        HospitalPaymentSettings::where('hospital_id', $hospital->id)
            ->where('payment_gateway', $request->payment_gateway)
            ->update(['is_active' => false]);

        // Create new payment setting
        HospitalPaymentSettings::create([
            'hospital_id' => $hospital->id,
            'payment_gateway' => $request->payment_gateway,
            'public_key' => $request->public_key,
            'secret_key' => $request->secret_key,
            'is_active' => true,
        ]);

        return redirect()->route('hospital.payment.settings')
            ->with('success', 'Payment settings saved successfully!');
    }
}