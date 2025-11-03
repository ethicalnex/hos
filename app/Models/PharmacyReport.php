<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PharmacyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id', 'report_type', 'start_date', 'end_date', 
        'data', 'generated_by', 'report_path', 'is_shared_with_admin'
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}