<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'logo',
        'is_active',
        'subscription_ends_at',
         'subscription_plan_id',    // ← ADD THIS
    'is_trial_active',        // ← ADD THIS  
    'trial_ends_at',          // ← ADD THIS
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscription_ends_at' => 'datetime',
        'trial_ends_at' => 'date', 
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function paymentSettings()
    {
        return $this->hasMany(HospitalPaymentSettings::class);
    }

    public function activePaymentSettings()
    {
        return $this->hasMany(HospitalPaymentSettings::class)->where('is_active', true);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // app/Models/Hospital.php
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Helpers
    public function isSubscribed()
    {
        return $this->subscription_ends_at && $this->subscription_ends_at->isFuture();
    }

    public function getActivePaymentGateway($gateway)
    {
        return $this->activePaymentSettings()->where('payment_gateway', $gateway)->first();
    }
    // Add these relationships
    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(HospitalSubscription::class);
    }
    public function hasFeature($feature)
    {
        return $this->subscriptionPlan && $this->subscriptionPlan->hasFeature($feature);
    }

    public function isTrialActive()
    {
        return $this->is_trial_active && $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function hasActiveSubscription()
    {
        return $this->currentSubscription() && $this->currentSubscription()->ends_at->isFuture();
    }

    public function currentSubscription()
    {
        return $this->subscriptions()->where('is_active', true)->latest()->first();
    }

    public function getFeatureName($feature)
    {
        return $this->subscriptionPlan ? $this->subscriptionPlan->getFeatureName($feature) : $feature;
    }
    

        // app/Models/Hospital.php
    public function departments()
    {
        return $this->hasMany(\App\Models\Department::class);
    }
}