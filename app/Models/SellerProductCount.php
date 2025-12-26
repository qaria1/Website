<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerProductCount extends Model
{
    use HasFactory;

    protected $fillable = ['count_left', 'seller_subscription_id'];

    public function sellerSubscription()
    {
        return $this->belongsTo(SellerSubscription::class, 'seller_subscription_id', 'id');
    }
}
