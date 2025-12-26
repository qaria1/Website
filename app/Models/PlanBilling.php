<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanBilling extends Model
{
    use HasFactory;

    protected $table = 'plan_billings';
    protected $fillable = [
        'price',
        'validity',
        'plan_id',
        'billing_type_id',
        'available'
    ];

    protected $casts = [
        'price' => 'float',
        'validity' => 'integer',
        'plan_id' => 'integer',
        'billing_type_id' => 'integer',
        'available' => 'integer',
    ];

    public function billingType()
    {
        return $this->belongsTo(BillingType::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }
}
