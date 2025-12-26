<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_active'];

    public function plan(){
        return $this->belongsToMany(SubscriptionPlan::class, 'subscription_plan_features', 'feature_id', 'plan_id');
    }
}
