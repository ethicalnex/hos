<?php
namespace App\Http\Controllers\Traits;

use App\Models\Payment;
use App\Services\PaymentGatewayFactory;
use Illuminate\Support\Str;

trait PaymentTrait
{
    protected function initiatePayment(
        int $hospitalId,
        string $gateway,
        string $email,
        int $amount, // in kobo
        string $paymentType,
        ?int $userId = null,
        array $metadata = [],
        string $callbackRoute = 'hospital.dashboard'
    ) {
        // Ensure hospital_id is in metadata
        $metadata['hospital_id'] = $hospitalId;

        $payment = Payment::create([
            'hospital_id' => $hospitalId,
            'user_id' => $userId ?? auth()->id(),
            'payment_type' => $paymentType,
            'payment_gateway' => $gateway,
            'reference' => (string) Str::uuid(),
            'amount' => $amount,
            'status' => Payment::STATUS_PENDING,
            'metadata' => $metadata,
        ]);

        $service = PaymentGatewayFactory::make($gateway, $hospitalId);
        $result = $service->initialize([
            'email' => $email,
            'amount' => $amount,
            'reference' => $payment->reference,
            'callback_url' => route($callbackRoute),
            'metadata' => $metadata,
        ]);

        return $result['authorization_url'];
    }
}