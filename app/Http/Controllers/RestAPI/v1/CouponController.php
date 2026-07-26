<?php

namespace App\Http\Controllers\RestAPI\v1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Utils\CartManager;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    public function __construct(
        private readonly \App\Services\CouponValidationService $couponValidationService
    ) {}

    public function list(Request $request){

        $customer_id = $request->user() ? $request->user()->id : '0';

        $coupons = Coupon::with('seller.shop')
            ->withCount(['order'=>function($query) use($customer_id){
                $query->where(['customer_id'=>$customer_id]);
            }])
            ->where(['status' => 1])
            ->whereIn('customer_id',[$customer_id, '0'])
            ->whereDate('start_date', '<=', now())
            ->whereDate('expire_date', '>=', now())
            ->select('coupons.*', DB::raw('DATE(expire_date) as plain_expire_date'))
            ->inRandomOrder()
            ->paginate($request['limit'], ['*'], 'page', $request['offset']);

        return [
            'total_size' => $coupons->total(),
            'limit' => (int)$request['limit'],
            'offset' => (int)$request['offset'],
            'coupons' => $coupons->items()
        ];
    }

    public function applicable_list(Request $request) {
        $customer_id = $request->user() ? $request->user()->id : '0';

        $cart_data = Cart::where(['customer_id'=> $customer_id, 'is_guest'=>'0'])->pluck('product_id');
        $product_group = Product::whereIn('id', $cart_data)->select('id', 'added_by', 'user_id')->get();

        if($cart_data->count() > 0 && $product_group->count() > 0) {
            $coupons = Coupon::with('seller.shop')
                ->select('coupons.*', DB::raw('DATE(expire_date) as plain_expire_date'))
                ->withCount(['order'=>function($query) use($customer_id){
                    $query->where(['customer_id'=>$customer_id]);
                }])
                ->when($product_group->where('added_by', 'seller')->count() > 0, function($query) use($product_group) {
                    $query->where(['coupon_bearer'=>'seller'])
                        ->whereIn('seller_id', $product_group
                        ->where('added_by', 'seller')
                        ->pluck('user_id'))
                        ->orWhereIn('seller_id', ['0']);
                })
                ->when($product_group->where('added_by', 'admin')->count() > 0, function($query){
                    $query->where(['coupon_bearer'=>'inhouse']);
                })
                ->where(['status' => 1])
                ->whereIn('customer_id',[$customer_id, '0'])
                ->whereDate('start_date', '<=', now())
                ->whereDate('expire_date', '>=', now())
                ->get();


            $coupons = $coupons->filter(function($data) {
                return (($data->order_count < $data->limit) || empty($data->limit)) && ($data->start_date <= now() && $data->expire_date >= now());
            })->values();

            $customer_order_count = Order::where('customer_id', $customer_id)->count();
            if($customer_order_count > 0) {
                $coupons = $coupons->whereNotIn('coupon_type', ['first_order']);
            }
        }
        return response()->json($coupons ?? [], 200);
    }

    public function apply(Request $request)
    {
        $user = $request->user();
        $cartItems = CartManager::get_cart_for_api($request);

        $result = $this->couponValidationService->validateAndCalculate(
            code: $request['code'] ?? '',
            user: $user,
            cartItems: $cartItems
        );

        if (!$result['status']) {
            return response()->json($result['message'], 202);
        }

        $coupon = $result['coupon'];

        return response()->json([
            'coupon_discount' => $result['discount'],
            'coupon_type' => $coupon->coupon_type
        ], 200);
    }

    public function get_seller_wise_coupon(Request $request, $seller_id){
        $seller_ids = ['0'];
        $coupons = Coupon::with('seller.shop')
            ->where(['status' => 1])
            ->whereDate('start_date', '<=', date('Y-m-d'))
            ->whereDate('expire_date', '>=', date('Y-m-d'))
            ->when($seller_id == '0', function ($query) use($seller_ids){
                $seller_ids[] = NULL;
                return $query->whereIn('seller_id', $seller_ids);
            })
            ->when($seller_id != '0', function ($query) use ($seller_ids, $seller_id) {
                $seller_ids[] = $seller_id;
                return $query->whereIn('seller_id', $seller_ids);
            })
            ->select('coupons.*', DB::raw('DATE(expire_date) as plain_expire_date'))
            ->inRandomOrder()
            ->paginate($request['limit'], ['*'], 'page', $request['offset']);

        return [
            'total_size' => $coupons->total(),
            'limit' => (int)$request['limit'],
            'offset' => (int)$request['offset'],
            'coupons' => $coupons->items()
        ];
    }
}
