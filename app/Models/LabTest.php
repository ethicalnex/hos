<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LabTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id', 'category_id', 'name', 'description', 'price',
        'unit', 'normal_range', 'specimen_type', 'turnaround_time', 'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function category()
    {
        return $this->belongsTo(LabTestCategory::class);
    }

    public function orderTests()
    {
        return $this->hasMany(LabOrderTest::class);
    }
}