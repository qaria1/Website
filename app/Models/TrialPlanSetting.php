<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrialPlanSetting extends Model
{
    use HasFactory;

    protected $fillable = ['duration_in_days', 'plan_id', 'is_active'];

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id', 'id');
    }
}
