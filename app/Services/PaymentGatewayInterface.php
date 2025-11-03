<?php
namespace App\Services;

interface PaymentGatewayInterface
{
    public function initialize(array $data): array;
    public function verifyWebhook(array $payload, string $signature, string $webhookSecret): bool;
    public function extractReference(array $payload): string;
    public function extractStatus(array $payload): string;
}