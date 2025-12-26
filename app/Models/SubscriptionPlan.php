<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'slug',
        'is_active',
        'max_product_lifecycle',
        'max_product_upload',
        'discount',
        'product_top_search',
        'item_verification',
        'product_photoshoot',
        'free_delivery',
        'available_vendors',
        'status'
    ];

    protected $casts = [
        'name' => 'string',
        'code' => 'string',
        'slug' => 'string',
        'is_active' => 'boolean',
        'max_product_lifecycle' => 'integer',
        'max_product_upload' => 'string',
        'discount' => 'integer',
        'product_top_search' => 'integer',
        'item_verification'=> 'integer',
        'product_photoshoot'=> 'integer',
        'free_delivery'=> 'integer',
        'available_vendors' => 'integer',
        'status' => 'boolean'
    ];

    public function features()
    {
        // return $this->hasMany(SubscriptionPlanFeature::class);
        return $this->belongsToMany(Feature::class, 'subscription_plan_features', 'plan_id')->withPivot('value');
    }

    public function subscriptions()
    {
        return $this->hasMany(SellerSubscription::class, 'plan_id');
    }

    public function billingTypes()
    {
        // return $this->hasMany(PlanBilling::class);
        return $this->belongsToMany(BillingType::class, 'plan_billings', 'plan_id')->withPivot('price', 'available');
    }

    public function transactions()
    {
        return $this->hasMany(SubscriptionTransaction::class, 'plan_id');
    }

}
