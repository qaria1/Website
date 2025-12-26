<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerWaitingList extends Model
{
    use HasFactory;

    protected $fillable = ['seller_id', 'status', 'position'];

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'id');
    }

}
