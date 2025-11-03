<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PharmacyInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id', 'item_id', 'quantity', 'expiry_date', 'batch_number'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'expiry_date' => 'date',
    ];

    protected $table = 'pharmacy_inventory';

    public function item()
    {
                return $this->belongsTo(PharmacyItem::class, 'item_id');
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}