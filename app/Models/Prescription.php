<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id', 'patient_id', 'doctor_id', 'pharmacist_id',
        'diagnosis', 'status', 'valid_until', 'notes'
    ];

    protected $casts = [
        'status' => 'string',
        'valid_until' => 'date',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function pharmacist()
    {
        return $this->belongsTo(User::class, 'pharmacist_id');
    }

    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    public function getIsExpiredAttribute()
    {
        if (!$this->valid_until) {
            return false;
        }
        return $this->valid_until->isPast();
    }
}