<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HospitalMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id', 'date', 'total_patients', 'new_patients', 
        'total_appointments', 'completed_appointments', 'cancelled_appointments',
        'total_revenue', 'total_staff', 'active_staff'
    ];

    protected $casts = [
        'date' => 'date',
        'total_revenue' => 'decimal:2',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}