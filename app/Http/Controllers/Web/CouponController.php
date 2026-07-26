<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CouponValidationService;
use App\Utils\CartManager;
use App\Utils\Helpers;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function __construct(
        private readonly CouponValidationService $couponValidationService
    ) {}

    public function apply(Request $request)
    {
        $user = auth('customer')->user() ?? auth('customer')->id() ?? 0;
        $cartItems = CartManager::get_cart();

        $result = $this->couponValidationService->validateAndCalculate(
            code: $request['code'] ?? '',
            user: $user,
            cartItems: $cartItems
        );

        if (!$result['status']) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 0,
                    'messages' => ['0' => $result['message']]
                ]);
            }
            Toastr::error($result['message']);
            return back();
        }

        $coupon = $result['coupon'];
        $discount = $result['discount'];
        $subtotal = $result['applicable_subtotal'] ?? 0;

        session()->put('coupon_code', $coupon->code);
        session()->put('coupon_type', $coupon->coupon_type);
        session()->put('coupon_discount', $discount);
        session()->put('coupon_bearer', $coupon->coupon_bearer);
        session()->put('coupon_seller_id', $coupon->seller_id);

        if ($request->ajax()) {
            return response()->json([
                'status' => 1,
                'discount' => Helpers::currency_converter($discount),
                'total' => Helpers::currency_converter(max(0, $subtotal - $discount)),
                'messages' => ['0' => translate('coupon_applied_successfully').'!']
            ]);
        }

        Toastr::success(translate('coupon_applied_successfully'));
        return back();
    }
}
