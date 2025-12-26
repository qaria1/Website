<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BillingType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'duration_in_days'
    ];

    public function planBillings()
    {
        return $this->hasMany(PlanBilling::class);
    }

    public function plan()
    {
        return $this->hasMany(SellerSubscription::class, 'billing_type_id');
    }
}
