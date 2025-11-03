<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id', 'patient_id', 'doctor_id', 'nurse_id',
        'temperature', 'blood_pressure', 'pulse', 'respiratory_rate',
        'weight', 'height', 'symptoms',
        'diagnosis', 'treatment_plan', 'doctor_notes', 'nurse_notes'
    ];

    public function patient() { return $this->belongsTo(Patient::class); }
    public function appointment() { return $this->belongsTo(Appointment::class); }
    public function doctor() { return $this->belongsTo(User::class, 'doctor_id'); }
    public function nurse() { return $this->belongsTo(User::class, 'nurse_id'); }
}