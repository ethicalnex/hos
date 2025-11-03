<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LabOrderTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'test_id', 'result_value', 'result_status', 'notes', 'is_completed'
    ];

    protected $casts = [
        'result_value' => 'decimal:4',
        'is_completed' => 'boolean',
    ];

    public function order()
    {
        return $this->belongsTo(LabOrder::class);
    }

    public function test()
    {
        return $this->belongsTo(LabTest::class);
    }

    public function getResultStatusAttribute($value)
    {
        if ($this->result_value === null) {
            return null;
        }
        
        $test = $this->test;
        if (!$test->normal_range) {
            return 'Normal';
        }

        // Parse normal range (e.g., "4.5-11.0" or "<5.0" or ">100")
        $range = $test->normal_range;
        if (strpos($range, '-') !== false) {
            [$min, $max] = explode('-', $range);
            if ($this->result_value < floatval($min)) {
                return 'Low';
            } elseif ($this->result_value > floatval($max)) {
                return 'High';
            }
        } elseif (strpos($range, '<') === 0) {
            $max = floatval(substr($range, 1));
            if ($this->result_value > $max) {
                return 'High';
            }
        } elseif (strpos($range, '>') === 0) {
            $min = floatval(substr($range, 1));
            if ($this->result_value < $min) {
                return 'Low';
            }
        }
        
        return 'Normal';
    }
}