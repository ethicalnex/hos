<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id', 'plan_id', 'action', 'notes'
    ];

    protected $casts = [
        'action' => 'string',
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