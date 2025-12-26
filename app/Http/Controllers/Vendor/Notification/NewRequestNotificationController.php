<?php

namespace App\Http\Controllers\Vendor\Notification;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\RefundRequest;
use App\Http\Controllers\Controller;

class NewRequestNotificationController extends Controller
{
    public function __construct(){}

    public function index()
    {
        $approvedProducts = Product::where([
            'request_status' => 1,
            'checked_by_seller' => 0,
            'added_by' => 'seller',
            'user_id' => auth('seller')->id()
        ])->count();

        $rejectedProducts = Product::where([
            'request_status' => 2,
            'checked_by_seller' => 0,
            'added_by' => 'seller',
            'user_id' => auth('seller')->id()
        ])->count();

        $rejectedRefundRequests = RefundRequest::where([
            'status' => 'rejected', 'checked_by_seller' => 0
        ])->count();

        return response()->json([
            'approvedProducts' => $approvedProducts,
            'rejectedProducts' => $rejectedProducts,
            'rejectedRefundRequests' => $rejectedRefundRequests,
        ]);
    }
}
