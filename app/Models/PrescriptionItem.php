<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PrescriptionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_id', 'item_id', 'dosage', 'frequency', 'quantity', 'instructions', 'is_dispensed'
    ];

    protected $casts = [
        'is_dispensed' => 'boolean',
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function item()
    {
        return $this->belongsTo(PharmacyItem::class);
    }
}