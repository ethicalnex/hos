<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalPaymentSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id',
        'payment_gateway',
        'public_key',
        'secret_key',
        'webhook_secret',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    // Encryption for sensitive data
    public function setSecretKeyAttribute($value)
    {
        $this->attributes['secret_key'] = encrypt($value);
    }

    public function getSecretKeyAttribute($value)
    {
        return $value ? decrypt($value) : null;
    }

    public static function getActiveGateway(string $gateway, int $hospitalId): ?self
    {
        return self::where('hospital_id', $hospitalId)
            ->where('payment_gateway', $gateway)
            ->where('is_active', true)
            ->first();
    }

    public function setWebhookSecretAttribute($value)
    {
        $this->attributes['webhook_secret'] = $value ? encrypt($value) : null;
    }

    public function getWebhookSecretAttribute($value)
    {
        return $value ? decrypt($value) : null;
    }
}