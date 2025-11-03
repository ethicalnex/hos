<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['hospital_id', 'name', 'description', 'is_active'];

    public function headDoctor()
    {
        return $this->belongsTo(User::class, 'head_doctor_id');
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}