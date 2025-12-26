<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionRule extends Model
{
    use HasFactory;
    protected $table = 'commission_rules';
    protected $fillable = [
        'seller_id',
        'min_price',
        'max_price',
        'commission_percent'
    ];
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
}
