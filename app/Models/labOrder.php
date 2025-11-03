<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LabOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id', 'patient_id', 'doctor_id', 'lab_technician_id', 'status', 'notes'
    ];

    protected $casts = [
        'status' => 'string',
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

    public function labTechnician()
    {
        return $this->belongsTo(User::class, 'lab_technician_id');
    }

    public function tests()
    {
        return $this->hasMany(LabOrderTest::class);
    }

    public function report()
    {
        return $this->hasOne(LabReport::class);
    }
}