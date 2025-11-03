<?php
namespace App\Services;

use App\Models\HospitalPaymentSettings;
use InvalidArgumentException;

class PaymentGatewayFactory
{
    public static function make(string $gateway, int $hospitalId): PaymentGatewayInterface
    {
        $settings = HospitalPaymentSettings::getActiveGateway($gateway, $hospitalId);
        if (!$settings) {
            throw new InvalidArgumentException("No active {$gateway} settings for this hospital.");
        }

        return match ($gateway) {
            'paystack' => new PaystackService($settings->secret_key),
            'flutterwave' => new FlutterwaveService($settings->secret_key),
            default => throw new InvalidArgumentException("Unsupported gateway: {$gateway}")
        };
    }

    public static function getWebhookSecret(string $gateway, int $hospitalId): ?string
    {
        $settings = HospitalPaymentSettings::getActiveGateway($gateway, $hospitalId);
        return $settings?->webhook_secret;
    }
}