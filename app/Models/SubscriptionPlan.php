<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'currency', 'trial_days', 'billing_cycle', 
        'is_active', 'features', 'max_staff', 'max_patients', 'max_departments'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'features' => 'array',
    ];

    public function hospitals()
    {
        return $this->hasMany(Hospital::class);
    }

    public function hasFeature($feature)
    {
        return $this->features[$feature] ?? false;
    }

    public function getFeatureName($feature)
    {
        $features = [
            'emr' => 'EMR',
            'lab' => 'Lab Management',
            'pharmacy' => 'Pharmacy',
            'billing' => 'Billing & Invoicing',
            'appointments' => 'Appointment Booking',
            'reports' => 'Reports & Analytics',
            'ai' => 'AI Diagnostics',
            'mobile_app' => 'Mobile App Access',
            'sms' => 'SMS Reminders',
            'api' => 'API Integration',
        ];
        return $features[$feature] ?? $feature;
    }
}