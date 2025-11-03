<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\PaymentGatewayFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function paystack(Request $request)
    {
        return $this->processWebhook($request, 'paystack', $request->header('x-paystack-signature'));
    }

    public function flutterwave(Request $request)
    {
        return $this->processWebhook($request, 'flutterwave', $request->header('verif-hash'));
    }

    private function processWebhook(Request $request, string $gateway, ?string $signature)
    {
        $payload = $request->all();
        $reference = $this->getReference($gateway, $payload);

        $payment = Payment::where('reference', $reference)->first();
        if (!$payment) {
            Log::warning("Webhook: Payment not found", ['reference' => $reference, 'gateway' => $gateway]);
            return response()->noContent(404);
        }

        $webhookSecret = PaymentGatewayFactory::getWebhookSecret($gateway, $payment->hospital_id);
        if (!$webhookSecret || !$signature) {
            Log::error("Webhook: Missing secret or signature", ['hospital_id' => $payment->hospital_id]);
            return response()->noContent(401);
        }

        $service = PaymentGatewayFactory::make($gateway, $payment->hospital_id);
        if (!$service->verifyWebhook($payload, $signature, $webhookSecret)) {
            Log::warning("Webhook: Signature mismatch", ['reference' => $reference]);
            return response()->noContent(401);
        }

        $newStatus = $service->extractStatus($payload);
        $gatewayRef = $gateway === 'paystack' ? $payload['data']['id'] : $payload['data']['id'];

        if ($payment->status !== Payment::STATUS_SUCCESSFUL) {
            $payment->update([
                'status' => $newStatus,
                'gateway_reference' => $gatewayRef,
            ]);

            if ($newStatus === Payment::STATUS_SUCCESSFUL) {
                event(new \App\Events\PaymentSuccessful($payment));
            }
        }

        return response()->json(['status' => 'success']);
    }

    private function getReference(string $gateway, array $payload): string
    {
        return match ($gateway) {
            'paystack' => $payload['data']['reference'],
            'flutterwave' => $payload['data']['tx_ref'],
            default => throw new \Exception('Invalid gateway')
        };
    }
}