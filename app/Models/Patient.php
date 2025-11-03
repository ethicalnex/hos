<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'medical_record_number',
        'blood_type',
        'allergies',
        'insurance_provider',
        'insurance_number',
        'emergency_contact_name',
        'emergency_contact_phone',
        'address',
        'city',
        'state',
        'country',
        'date_of_birth',
        'gender',
        'marital_status',
        'occupation',
        'next_of_kin_name',
        'next_of_kin_relationship',
        'next_of_kin_phone',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'date_of_birth' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    // 🔑 Generate MRN: {hospital_slug}-{NNNN}
    public static function generateMedicalRecordNumber(int $hospitalId): string
    {
        $hospital = Hospital::findOrFail($hospitalId);
        $slug = preg_replace('/[^a-z0-9]+/', '-', strtolower($hospital->slug));
        $slug = trim($slug, '-');

        $count = self::where('hospital_id', $hospitalId)->count();
        $nextNumber = $count + 1;
        $paddedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return "{$slug}-{$paddedNumber}";
    }

    // 🔍 Search patients in a hospital
    public static function searchInHospital(int $hospitalId, string $query)
    {
        return self::where('hospital_id', $hospitalId)
            ->where(function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('medical_record_number', 'like', "%{$query}%")
                  ->orWhere('phone', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            });
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }
}