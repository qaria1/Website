<?php

namespace App\Http\Controllers\Admin\Notification;

use App\Models\Seller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RefundRequest;

class NewRequestNotificationController extends Controller
{
    public function __construct(){}

    public function index(Request $request)
    {
        $pendingSignups = Seller::where(['status' => 'pending', 'checked' => 0])->count();
        $newProductRequests = Product::where(['request_status' => 0, 'checked_by_admin' => 0])->count();
        $newRefundRequests = RefundRequest::where(['status' => 'pending', 'checked_by_admin' => 0])->count();

        return response()->json([
            'pendingSignups' => $pendingSignups,
            'newProductRequests' => $newProductRequests,
            'newRefundRequests' => $newRefundRequests,
        ]);
    }
}
