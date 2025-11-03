<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LabReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'report_path', 'is_shared_with_patient'
    ];

    protected $casts = [
        'is_shared_with_patient' => 'boolean',
    ];

    public function order()
    {
        return $this->belongsTo(LabOrder::class);
    }
}