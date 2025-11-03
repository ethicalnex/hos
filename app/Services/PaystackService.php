<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaystackService implements PaymentGatewayInterface
{
    public function __construct(protected string $secretKey) {}

    public function initialize(array $data): array
    {
        $response = Http::withToken($this->secretKey)
            ->timeout(30)
            ->post('https://api.paystack.co/transaction/initialize', [
                'email' => $data['email'],
                'amount' => $data['amount'], // kobo
                'reference' => $data['reference'],
                'callback_url' => $data['callback_url'],
                'metadata' => array_merge($data['metadata'], ['gateway' => 'paystack']),
            ]);

        $json = $response->json();
        if (!$json['status']) {
            throw new \Exception($json['message'] ?? 'Paystack error');
        }

        return [
            'authorization_url' => $json['data']['authorization_url'],
            'reference' => $json['data']['reference'],
        ];
    }

    public function verifyWebhook(array $payload, string $signature, string $webhookSecret): bool
    {
        return hash_equals($signature, hash_hmac('sha512', json_encode($payload, JSON_UNESCAPED_SLASHES), $webhookSecret));
    }

    public function extractReference(array $payload): string
    {
        return $payload['data']['reference'];
    }

    public function extractStatus(array $payload): string
    {
        return $payload['data']['status'] === 'success' ? Payment::STATUS_SUCCESSFUL : Payment::STATUS_FAILED;
    }
}