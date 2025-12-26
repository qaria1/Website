<?php

namespace App\Http\Controllers\RestAPI\v3\seller;

use App\Models\Seller;
use App\Models\PlanBilling;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\SellerSubscription;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\BillingType;
use App\Models\SubscriptionPlan;

class VendorSubscriptionController extends Controller
{

    public function index(Request $request){

        $seller = $request->seller;

        $subscriptionPlans = SubscriptionPlan::all();
        // $filters = [
        //     'billing_id' => $request['billing_id'],
        //     'subscription_plan_id' => $request['subscription_plan_id'],
        // ];

        $subscriptionHistoryQuery = SellerSubscription::query();

        // $subscriptionHistoryQuery->when(!empty($request->subscription_plan_id), function ($q) {
        //     return $q->where('plan_id', request('subscription_plan_id'));
        // });

        // $subscriptionHistoryQuery->when(!empty($request->billing_id), function ($q) {
        //     return $q->where('billing_type_id', request('billing_id'));
        // });

        $subscriptionHistory = $subscriptionHistoryQuery->where('seller_id', $seller['id'])
        ->with('plan')
        ->groupBy('plan_id')
        ->get();

        $findSeller = Seller::find($seller['id']);

        $activePlan = $findSeller?->getActivePlanForSeller($findSeller);

        if(isset($activePlan) && isset($findSeller)){

            if($activePlan?->max_product_upload && $activePlan?->max_product_upload != 'unlimited'){
                $productLeft = (int)$activePlan?->max_product_upload - (int)$findSeller->getTotalProductForActivePlan($findSeller) ?? 0;
            }else{
                $productLeft = 'unlimited';
            }
        }

        $noActivePlan = null;
        if(!$activePlan){
            $latestPlanRecord = SellerSubscription::with(['billingType', 'billingType.planBillings' => function($query){
                $query->join('seller_subscriptions', 'seller_subscriptions.plan_id', '=', 'plan_billings.plan_id');
            }, 'plan'])
            ->where('seller_id', $seller['id'])->orderBy('id', 'desc')->first();
            $noActivePlan = $latestPlanRecord;
        }

        if($activePlan){
            $activePlanPrice = PlanBilling::where(['plan_id' => $activePlan->plan_id, 'billing_type_id' => $activePlan->billing_type_id])->first();
            // $activePlanPriceTotalBill =
            // SellerSubscription::where(['seller_id' => $seller['id'], 'plan_id' => $activePlan->plan_id, 'billing_type_id' => $activePlan->billing_type_id])->count() ?? 0;
            // $activePlanPriceNumberOfUses = $activePlanPriceTotalBill;
            // $activePlanPerValue = BillingType::where('id', $activePlan->billing_type_id)->first();

            $activePlanName = $activePlan?->plan?->name;
            $activePlan['name'] = $activePlanName;
            $activePlan['product_left'] = $productLeft;
            $activePlan['total_bill'] =  \App\Models\SubscriptionTransaction::where('seller_id', $seller['id'])->where('plan_id', $activePlan->plan_id)->sum('paid_amount');

            $activePlan['number_of_uses'] = $activePlan?->total_package_renewed + 1;
            $activePlan['plan_price'] = $activePlanPrice?->price ?? 0;
            $activePlan['plan_per_day'] = $activePlan?->validity ?? 0;
        }

        $currentEndDate = SellerSubscription::where('seller_id', $seller['id'])->where('status', true)->first()?->current_end;
        $daysLeft = 0;
        if($currentEndDate){
            $currentPlanEndDate = \Carbon\Carbon::createFromFormat('Y-m-d', $currentEndDate);
            $currentDate = \Carbon\Carbon::now();
            $daysLeft = $currentPlanEndDate->diffInDays($currentDate);

            $activePlan['days_left'] = $daysLeft;

            if ($currentDate < $currentPlanEndDate && $daysLeft < 11) {
                $activePlan['days_left_reminder'] = true;
            }
        }

        return response()->json([
            'subscriptionHistory' => $subscriptionHistory,
            'seller' => $seller,
            'subscriptionPlans' => $subscriptionPlans,
            'active_plan' => $activePlan,
            'no_active_plan' => $noActivePlan
        ], 200);
    }

    public function get_history(Request $request){
        $seller = $request->seller;
        $plan = $request->plan;

        $planHistoryList = SellerSubscription::where('seller_id', $seller['id'])
        ->where('plan_id', $plan['id'])
        ->with('plan', 'billingType')
        ->get();

        if($planHistoryList->count() > 0){
            foreach ($planHistoryList as $plan) {
                $currentStartDate = \Carbon\Carbon::createFromFormat('Y-m-d', $plan->current_start);
                $currentEndDate = \Carbon\Carbon::createFromFormat('Y-m-d', $plan->current_end);
                $plan->total_days_used = $currentStartDate->diffInDays($currentEndDate);
            }
        }

        return response()->json([
            'planHistoryList' => $planHistoryList,
            'seller' => $seller,
        ], 200);

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

    public function get_plan_status(Request $request){

        $seller = Seller::find($request->seller['id']);
        $sellerActivePlan = $seller?->getActivePlanForSeller($seller) != null ? true : false;
        $sellerLeftProductCount = $seller?->getDefaultProductCountForPlan($seller) - $seller?->getTotalProductForActivePlan($seller);
        $sellerLeftProductCount = isset($sellerLeftProductCount) && $sellerLeftProductCount !== 0 ? true : false;

        $data = array();
        $data['has_seller_active_plan'] = $sellerActivePlan;
        $data['has_seller_enough_product_count'] = $sellerLeftProductCount;

        return response()->json($data, 200);
    }
}