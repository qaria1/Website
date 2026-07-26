<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $coupon_id
 * @property string $target_type
 * @property int $target_id
 * @property int $is_exclusion
 */
class CouponTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_id',
        'target_type',
        'target_id',
        'is_exclusion',
    ];

    protected $casts = [
        'coupon_id' => 'integer',
        'target_type' => 'string',
        'target_id' => 'integer',
        'is_exclusion' => 'integer',
    ];

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }
}
