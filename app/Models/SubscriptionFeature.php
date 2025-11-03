
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function plans()
    {
        return $this->belongsToMany(SubscriptionPlan::class, 'subscription_plan_features');
    }
}