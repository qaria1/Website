<?php

namespace App\Http\Controllers\Vendor\SubscriptionPlan;


use App\Models\Seller;
use App\Enums\WebConfigKey;
use App\Models\PlanBilling;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\SubscriptionPlan;
use Illuminate\Http\JsonResponse;
use App\Models\SellerSubscription;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionTransaction;

class VendorSubscriptionPlanController extends Controller
{
    public function index(Request $request){

        $subscriptionPlans = SubscriptionPlan::all();
        $filters = [
            'billing_id' => $request['billing_id'],
            'subscription_plan_id' => $request['subscription_plan_id'],
        ];

        $subscriptionHistoryQuery = SellerSubscription::query();

        $subscriptionHistoryQuery->when(!empty($request->subscription_plan_id), function ($q) {
            return $q->where('plan_id', request('subscription_plan_id'));
        });

        $subscriptionHistoryQuery->when(!empty($request->billing_id), function ($q) {
            return $q->where('billing_type_id', request('billing_id'));
        });

        $subscriptionHistory = $subscriptionHistoryQuery->where('seller_id', auth('seller')->id())
        ->groupBy('plan_id')
        ->get();

        $seller = Seller::findOrFail(auth('seller')->id());

        $seller_subscription = SellerSubscription::where('seller_id', $seller->id)->with(['billingType', 'plan'])->latest()->first();
        $total_bill = SubscriptionTransaction::where('seller_id', $seller->id)->where('plan_id', $seller_subscription->plan_id)->sum('paid_amount') ?? 0;

        $request->flash();

        return view('vendor-views.subscription.index', compact('subscriptionPlans', 'subscriptionHistory', 'seller', 'seller_subscription', 'total_bill'));
    }

    public function getHistory(Request $request){
        dd($request->all());
    }

    public function getBillingTypes(Request $request): JsonResponse
    {
        $planId = $request['plan_id'];
        $billingTypes = PlanBilling::where('plan_id', $planId)->get();

        $dropdown = '<option value="' . '" disabled selected>---' . translate("Select") . '---</option>';

        foreach ($billingTypes as $billing) {
            $dropdown .= '<option value="' . $billing->billing_type_id . '">' . $billing->billingType->name . ' - ' . setCurrencySymbol(amount: usdToDefaultCurrency(amount: $billing->price), currencyCode: getCurrencyCode(type: 'default')) . '</option>';
        }

        return response()->json([
            'select_tag' => $dropdown,
        ]);
    }

    public function transaction(Request $request){

        $key = explode(' ', $request['search']);

        $subscriptionPlans = SubscriptionPlan::all();
        $seller = Seller::findOrFail(auth('seller')->id());

        $from=$request?->start_date;
        $to= $request?->end_date;
        $filter = $request->query('filter', 'all');
        $transactions = SubscriptionTransaction::where('seller_id', $seller->id)->with('plan')
        ->when($filter == 'month', function ($query) {
            return $query->whereMonth('created_at', Carbon::now()->month);
        })
        ->when($filter == 'year', function ($query) {
            return $query->whereYear('created_at', Carbon::now()->year);
        })
        ->when(isset($key), function($q) use ($key){
            $q->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('paid_amount', 'like', "%{$value}%")
                        ->orWhere('reference', 'like', "%{$value}%")
                        ->orWheredate('created_at', 'like', "%{$value}%");
                }
            });
        })
        ->when(isset($from) && isset($to) , function($q)use($from,$to){
            $q->whereBetween('created_at', ["{$from}", "{$to} 23:59:59"]);
        })
        ->latest()->paginate(10);
        $total = $transactions?->total();

        $seller_subscription = SellerSubscription::where('seller_id', $seller->id)->with(['billingType', 'plan'])->latest()->first();

        return view('vendor-views.subscription.transaction', [
            'transactions' => $transactions,
            'filter' => $filter,
            'total' => $total,
            'seller' => $seller,
            'from' =>  $from,
            'to' =>  $to,
            'subscriptionPlans' => $subscriptionPlans,
            'seller_subscription' => $seller_subscription
        ]);
    }
}
