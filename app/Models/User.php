<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'hospital_id',
        'phone',
        'license_number',
        'specialization',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Role constants
    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_ADMIN = 'admin';
    const ROLE_DOCTOR = 'doctor';
    const ROLE_NURSE = 'nurse';
    const ROLE_LAB_TECH = 'lab_technician';
    const ROLE_PHARMACIST = 'pharmacist';
    const ROLE_RECEPTIONIST = 'receptionist';
    const ROLE_PATIENT = 'patient';

    public static $roles = [
        self::ROLE_SUPER_ADMIN => 'Super Administrator',
        self::ROLE_ADMIN => 'Hospital Administrator',
        self::ROLE_DOCTOR => 'Doctor',
        self::ROLE_NURSE => 'Nurse',
        self::ROLE_LAB_TECH => 'Lab Technician',
        self::ROLE_PHARMACIST => 'Pharmacist',
        self::ROLE_RECEPTIONIST => 'Receptionist',
        self::ROLE_PATIENT => 'Patient',
    ];

    // Relationships
    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    // Role check methods
    public function isSuperAdmin()
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isHospitalAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isStaff()
    {
        return in_array($this->role, [
            self::ROLE_ADMIN,
            self::ROLE_DOCTOR,
            self::ROLE_NURSE,
            self::ROLE_LAB_TECH,
            self::ROLE_PHARMACIST,
            self::ROLE_RECEPTIONIST,
        ]);
    }

    // Add to User model
public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/avatars/' . $this->avatar);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    public function isPatient()
    {
        return $this->role === self::ROLE_PATIENT;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByHospital($query, $hospitalId)
    {
        return $query->where('hospital_id', $hospitalId);
    }

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }
    // In User model
    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class, 'doctor_id');
    }

    public function scopeStaff($query)
    {
        return $query->whereIn('role', [
            self::ROLE_ADMIN,
            self::ROLE_DOCTOR,
            self::ROLE_NURSE,
            self::ROLE_LAB_TECH,
            self::ROLE_PHARMACIST,
            self::ROLE_RECEPTIONIST,
        ]);
    }
}