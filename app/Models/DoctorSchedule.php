<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    protected $fillable = [
        'doctor_id', 'hospital_id', 'day_of_week', 
        'start_time', 'end_time', 'slot_duration', 'is_available'
    ];
    protected $casts = [
    'start_time' => 'datetime:H:i',
    'end_time' => 'datetime:H:i',
];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}