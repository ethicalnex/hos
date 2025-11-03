<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuperAdminPaymentSettings extends Model
{
    protected $fillable = [
        'payment_gateway', 'public_key', 'secret_key', 'webhook_secret', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Encryption for sensitive data
    public function setSecretKeyAttribute($value)
    {
        $this->attributes['secret_key'] = encrypt($value);
    }

    public function getSecretKeyAttribute($value)
    {
        return $value ? decrypt($value) : null;
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