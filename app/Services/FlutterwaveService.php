<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class FlutterwaveService implements PaymentGatewayInterface
{
    public function __construct(protected string $secretKey) {}

    public function initialize(array $data): array
    {
        $response = Http::withToken($this->secretKey)
            ->timeout(30)
            ->post('https://api.flutterwave.com/v3/payments', [
                'tx_ref' => $data['reference'],
                'amount' => $data['amount'] / 100, // Flutterwave expects NGN (not kobo)
                'currency' => $data['currency'] ?? 'NGN',
                'redirect_url' => $data['callback_url'],
                'customer' => ['email' => $data['email']],
                'meta' => array_merge($data['metadata'], ['gateway' => 'flutterwave']),
            ]);

        $json = $response->json();
        if ($json['status'] !== 'success') {
            throw new \Exception($json['message'] ?? 'Flutterwave error');
        }

        return [
            'authorization_url' => $json['data']['link'],
            'reference' => $data['reference'],
        ];
    }

    public function verifyWebhook(array $payload, string $signature, string $webhookSecret): bool
    {
        return hash_equals($signature, hash_hmac('sha256', json_encode($payload, JSON_UNESCAPED_SLASHES), $webhookSecret));
    }

    public function extractReference(array $payload): string
    {
        return $payload['data']['tx_ref'];
    }

    public function extractStatus(array $payload): string
    {
        return $payload['data']['status'] === 'successful' ? Payment::STATUS_SUCCESSFUL : Payment::STATUS_FAILED;
    }
}