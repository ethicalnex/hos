<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PharmacySale extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id', 'prescription_id', 'patient_id', 'pharmacist_id',
        'total_amount', 'payment_method', 'status', 'notes'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'status' => 'string',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function pharmacist()
    {
        return $this->belongsTo(User::class, 'pharmacist_id');
    }
}