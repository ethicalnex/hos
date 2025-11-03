<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionRenewal extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id', 'plan_id', 'renewal_date', 'amount', 
        'payment_gateway', 'payment_reference', 'is_paid'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'renewal_date' => 'date',
        'is_paid' => 'boolean',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }
}