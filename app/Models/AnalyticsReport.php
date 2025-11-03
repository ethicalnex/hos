<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnalyticsReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id', 'report_type', 'data', 'notes'
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}