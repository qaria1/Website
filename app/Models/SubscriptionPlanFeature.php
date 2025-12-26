<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlanFeature extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'value', 'plan_id', 'is_active'];

    public function plan()
    {
        return $this->belongsToMany(SubscriptionPlan::class, 'plan_id', 'id');
    }
}
