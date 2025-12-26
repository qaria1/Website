<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionTransaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'package_details' => 'array',
        'id'=> 'string',
        'plan_id'=>'integer',
        'seller_id'=>'integer',
        'status'=>'integer',
        'payment_method'=>'string',
        'paid_amount'=>'float',
        'validity'=>'integer',

    ];

    public function seller()
    {
        return $this->hasOne(Seller::class,'id', 'seller_id');
    }
    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id', 'id');
    }
}