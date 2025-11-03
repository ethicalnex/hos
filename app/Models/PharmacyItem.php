<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PharmacyItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id', 'name', 'description', 'category', 'form', 'strength', 'price', 'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function inventory()
    {
        return $this->hasOne(PharmacyInventory::class, 'item_id');
    }

    public function getAvailableQuantityAttribute()
    {
        return $this->inventory?->quantity ?? 0;
    }
}