<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'hospital_id', 'patient_id', 'doctor_id', 'nurse_id',
        'scheduled_time', 'duration', 'reason', 'status', 'notes', 'service_id',
    'payment_id'
    ];

    protected $casts = [
        'scheduled_time' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function nurse()
    {
        return $this->belongsTo(User::class, 'nurse_id');
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}