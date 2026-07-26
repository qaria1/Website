<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $coupon_id
 * @property int $order_id
 * @property int $customer_id
 * @property float $discount_amount
 * @property float $seller_bearer_amount
 * @property float $inhouse_bearer_amount
 * @property string $redeemed_at
 */
class CouponRedemption extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_id',
        'order_id',
        'customer_id',
        'discount_amount',
        'seller_bearer_amount',
        'inhouse_bearer_amount',
        'redeemed_at',
    ];

    protected $casts = [
        'coupon_id' => 'integer',
        'order_id' => 'integer',
        'customer_id' => 'integer',
        'discount_amount' => 'float',
        'seller_bearer_amount' => 'float',
        'inhouse_bearer_amount' => 'float',
        'redeemed_at' => 'datetime',
    ];

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
