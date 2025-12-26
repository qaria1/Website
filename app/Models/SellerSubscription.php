<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'plan_id',
        'billing_type_id',
        'price',
        'validity',

        'max_product_lifecycle',
        'max_product_upload',
        'discount',
        'product_top_search',
        'item_verification',
        'product_photoshoot',
        'free_delivery',
        'available_vendors',
        'total_package_renewed',

        'start_date',
        'current_start',
        'current_end',
        'is_free',
        'is_trial',
        'status',
        // 'upgraded_at',
        // 'upgraded_from_plan_id',
        // 'upgraded_to_plan_id',
        // 'downgraded_at',
        // 'downgraded_from_plan_id',
        // 'downgraded_to_plan_id',
    ];

    protected $casts = [
        'seller_id' => 'integer',
        'plan_id' => 'integer',
        'billing_type_id' => 'integer',
        'price' => 'float',
        'validity' => 'integer',

        'max_product_lifecycle' => 'integer',
        'max_product_upload' => 'string',
        'discount' => 'integer',
        'product_top_search' => 'integer',
        'item_verification'=> 'integer',
        'product_photoshoot'=> 'integer',
        'free_delivery'=> 'integer',
        'available_vendors' => 'integer',
        'total_package_renewed' => 'integer',

        'start_date' => 'date',
        // 'current_start' => 'datetime',
        // 'current_end' => 'datetime',
        'is_free' => 'boolean',
        'is_trial' => 'boolean',
        'status' => 'boolean',
        // 'upgraded_at' => 'date',
        // 'upgraded_from_plan_id' => 'integer',
        // 'upgraded_to_plan_id' => 'integer',
        // 'downgraded_at' => 'integer',
        // 'downgraded_from_plan_id' => 'integer',
        // 'downgraded_to_plan_id' => 'integer',
    ];

    // Include the count of products in the result
    protected $withCount = ['products', 'activeProducts', 'archivedProducts'];

    public function getMaxProductUploadAttribute($value){
        return $value == 'unlimited' ? translate('unlimited') : (int)$value;
    }

    public function transactions()
    {
        return $this->hasMany(SubscriptionTransaction::class,'seller_id', 'seller_id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'id');
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function billingType()
    {
        return $this->belongsTo(BillingType::class, 'billing_type_id');
    }

    public static function getFreePlanSubscribeCount(){

        $freePlanId = SubscriptionPlan::where('slug', FREE_PLAN)->first()?->id;

        $count = SellerSubscription::where('plan_id', $freePlanId)->where('status', true)->count();
        return $count;
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'seller_subscription_id');
    }

    public function activeProducts()
    {
        return $this->hasMany(Product::class, 'seller_subscription_id')->where('status', true);
    }

    public function archivedProducts()
    {
        return $this->hasMany(Product::class, 'seller_subscription_id')->where('is_lifetime_ended', true);
    }
}
