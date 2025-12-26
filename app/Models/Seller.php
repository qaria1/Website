<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use App\Models\SubscriptionPlan;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\OrderTransaction;

/**
 * @property int $id
 * @property string $f_name
 * @property string $l_name
 * @property string $phone
 * @property string $sex
 * @property string $age
 * @property string $brand_id
 * @property string $image
 * @property string $email
 * @property string $password
 * @property string $status
 * @property string $bank_name
 * @property string $branch
 * @property string $account_no
 * @property string $holder_name
 * @property string $auth_token
 * @property float $sales_commission_percentage
 * @property float $gst
 * @property string $cm_firebase_token
 * @property string $pos_status
 * @property float $minimum_order_amount
 * @property string $free_delivery_status
 * @property float $free_delivery_over_amount
 * @property string $app_language
 * @property string $checked
 */
class Seller extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'free_delivery_over_amount',
        'sex',
        'age',
        'brand_id',
        'checked',
    ];
    protected $casts = [
        'id' => 'integer',
        'orders_count' => 'integer',
        'product_count' => 'integer',
        'pos+status' => 'integer',
        'brand_id' => 'integer',
        'checked' => 'boolean',
        'sex' => 'string',
        'age' => 'integer',
    ];




    public function scopeApproved($query)
    {
        return $query->where(['status' => 'approved']);
    }

    public function shop(): HasOne
    {
        return $this->hasOne(Shop::class, 'seller_id');
    }

    public function shops(): HasMany
    {
        return $this->hasMany(Shop::class, 'seller_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    public function product(): HasMany
    {
        return $this->hasMany(Product::class, 'user_id')->where(['added_by' => 'seller']);
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(SellerWallet::class);
    }

    public function coupon(): HasMany
    {
        return $this->hasMany(Coupon::class, 'seller_id')
            ->where(['coupon_bearer' => 'seller', 'status' => 1])
            ->whereDate('start_date', '<=', date('Y-m-d'))
            ->whereDate('expire_date', '>=', date('Y-m-d'));
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(SellerSubscription::class);
    }


    public function subscriptionPlans()
    {
        return $this->belongsToMany(SubscriptionPlan::class, 'seller_subscriptions')->withTimestamps();
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function waitingList(){
        return $this->hasOne(SellerWaitingList::class);
    }

    public static function getActivePlanForSeller($seller){
        $activePlan = SellerSubscription::where('seller_id', $seller->id)
        ->where('status', true)->first();

        return $activePlan;
    }

    public static function getTotalProductForActivePlan($seller){
        $totalProductUploaded = Product::where('user_id', $seller->id)
        ->where('seller_subscription_id', self::getActivePlanForSeller($seller)?->id)->count();

        return $totalProductUploaded;
    }

    public static function getDefaultProductCountForPlan($seller){
        $seller_subscription = SellerSubscription::where('seller_id', $seller->id)->with(['billingType', 'plan'])->latest()->first();
        $defaultProductCount = $seller_subscription?->max_product_upload;

        return $defaultProductCount;
    }

    public static function isProductArchived($product){
        $isArchived = Product::where('id', $product?->id)->first();

        return isset($isArchived) && $isArchived->is_lifetime_ended == true ? true : false;
    }

    public static function getVendorEstimatedProfit($seller){

        $orderTransaction = new OrderTransaction;
        $estimatedProfit = '';

        $totalEarnings = $orderTransaction
            ->where('seller_id', $seller->id)
            ->where(['status' => 'disburse'])
            ->select(\Illuminate\Support\Facades\DB::raw('IFNULL(sum(seller_amount),0) as sums'))
            ->get()
            ->toArray();

        $isPurchasePriceSet = Product::where('user_id', $seller->id)
        ->where('added_by', 'seller')
        ->where('purchase_price', '!=', 0)
        ->get();

        if(count($totalEarnings) > 0 && $isPurchasePriceSet->count() > 0){
            $estimatedProfit = $seller?->product?->sum(function ($product) {
                if($product?->purchase_price != 0){
                    return $product?->unit_price - $product?->purchase_price;
                }
            });
        }

        return $estimatedProfit;
    }

    public static function getVendorEarningProfit($seller){

        $orderTransaction = new OrderTransaction;
        $earningProfit = '';

        $totalEarnings = $orderTransaction
            ->where('seller_id', $seller->id)
            ->where(['status' => 'disburse'])
            ->select(\Illuminate\Support\Facades\DB::raw('IFNULL(sum(seller_amount),0) as sums'))
            ->get()
            ->toArray();

        $isPurchasePriceSet = Product::where('user_id', $seller->id)
            ->where('added_by', 'seller')
            ->where('purchase_price', '!=', 0)
            ->get();

        if(count($totalEarnings) > 0 && $isPurchasePriceSet->count() > 0){

            $orders = $seller->orders()
            ->where('order_status', 'delivered')
            ->where('payment_status', 'paid')
            ->where('seller_id', $seller->id)
            ->with('details.product')
            ->get();

            $earningProfit = $orders?->sum(function ($order) {
                return $order->details?->sum(function ($detail) {

                    if($detail?->product && $detail?->product?->purchase_price != 0){
                        return ($detail?->product?->unit_price - $detail?->product?->purchase_price);
                    }

                });
            });
        }

        return $earningProfit;
    }

    public static function getTotalVendorEstimatedProfit(){

        $orderTransaction = new OrderTransaction;
        $estimatedProfit = '';
        $totalEstimatedProfit = 0;
        $sellers = Seller::all();

        $totalEarnings = $orderTransaction
            ->where('seller_is', 'seller')
            ->where(['status' => 'disburse'])
            ->select(\Illuminate\Support\Facades\DB::raw('IFNULL(sum(seller_amount),0) as sums'))
            ->get()
            ->toArray();

        $isPurchasePriceSet = Product::where('added_by', 'seller')
        ->where('purchase_price', '!=', 0)
        ->get();

        if(count($totalEarnings) > 0 && $isPurchasePriceSet->count() > 0){
            foreach ($sellers as $seller) {
                $estimatedProfit = $seller?->product?->sum(function ($product) {
                    return $product?->unit_price - $product?->purchase_price;
                });

                $totalEstimatedProfit += $estimatedProfit;

            }
        }

        return $totalEstimatedProfit;
    }
    public static function getTotalVendorEarningProfit(){

        $orderTransaction = new OrderTransaction;
        $earningProfit = '';
        $totalEarningProfit = 0;

        $sellers = Seller::all();

        $totalEarnings = $orderTransaction
            ->where('seller_is', 'seller')
            ->where(['status' => 'disburse'])
            ->select(\Illuminate\Support\Facades\DB::raw('IFNULL(sum(seller_amount),0) as sums'))
            ->get()
            ->toArray();

        $isPurchasePriceSet = Product::where('added_by', 'seller')
            ->where('purchase_price', '!=', 0)
            ->get();

        if(count($totalEarnings) > 0 && $isPurchasePriceSet->count() > 0){

            foreach ($sellers as $seller) {
                $orders = $seller->orders()
                ->where('order_status', 'delivered')
                ->where('payment_status', 'paid')
                ->where('seller_id', $seller->id)
                ->with('details.product')
                ->get();

                $earningProfit = $orders?->sum(function ($order) {
                    return $order->details?->sum(function ($detail) {

                        if($detail?->product){
                            return ($detail?->product?->unit_price - $detail?->product?->purchase_price);
                        }

                    });
                });

                $totalEarningProfit += $earningProfit;

            }
        }

        return $totalEarningProfit;
    }
}
