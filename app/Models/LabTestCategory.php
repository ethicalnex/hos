<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LabTestCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id', 'name', 'description', 'is_active'
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function tests()
    {
        return $this->hasMany(LabTest::class);
    }
}