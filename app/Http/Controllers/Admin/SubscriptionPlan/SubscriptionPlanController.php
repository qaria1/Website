<?php

namespace App\Http\Controllers\Admin\SubscriptionPlan;

use App\Models\Seller;
use App\Enums\WebConfigKey;
use App\Models\BillingType;
use App\Models\PlanBilling;
use App\Models\SellerWallet;
use Illuminate\Http\Request;
use App\Mail\FreePlanAssigned;
use Illuminate\Support\Carbon;
use App\Models\SubscriptionPlan;
use App\Models\TrialPlanSetting;
use App\Models\SellerWaitingList;
use Illuminate\Http\JsonResponse;
use App\Models\SellerSubscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;

use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\BaseController;
use function App\Utils\subscription_plan_chosen;

class SubscriptionPlanController extends BaseController
{

    public function __construct()
    {
    }
    public function index(Request|null $request, string $type = null): View
    {
        return view();
    }
    public function getBillingTypes(Request $request): JsonResponse
    {
        $planId = $request['plan_id'];
        $billingTypes = PlanBilling::where('plan_id', $planId)->get();

        $dropdown = '<option value="' . '" disabled selected>---' . translate("Select") . '---</option>';

        foreach ($billingTypes as $billing) {
            $dropdown .= '<option value="' . $billing?->billing_type_id . '">' . $billing?->billingType?->name . ' - ' . setCurrencySymbol(amount: usdToDefaultCurrency(amount: $billing?->price), currencyCode: getCurrencyCode(type: 'default')) . '</option>';
        }

        return response()->json([
            'select_tag' => $dropdown,
        ]);
    }

    public function updateSellerSubscription(Request $request)
    {
        // dd($request->all());

        $freePlanSubscribeCount = SellerSubscription::getFreePlanSubscribeCount();
        $maxVendorsForFreePlan = SubscriptionPlan::where('slug', FREE_PLAN)->first();

        $maxVendorsForFreePlan = $maxVendorsForFreePlan->features()->where('name', 'max_vendor_available')->first();

        // prevent free plan from being given to vendors if the free plan spot is not available
        if((!empty($request->subscription_plan) && $request->subscription_plan == FREE_PLAN) && $freePlanSubscribeCount > $maxVendorsForFreePlan?->pivot?->value){
            Toastr::error(translate('the_free_plan_has_filled_with_enough_vendors'), 'Warning',
            [
                "closeButton" => false,
                "debug" => false,
                "newestOnTop" => false,
                "progressBar" => false,
                "positionClass" => "toast-top-right",
                "preventDuplicates" => false,
                "onclick" => null,
                "showDuration" => "300",
                "hideDuration" => "1000",
                "timeOut" => "20000",
                "extendedTimeOut" => "1000",
                "showEasing" => "swing",
                "hideEasing" => "linear",
                "showMethod" => "fadeIn",
                "hideMethod" => "fadeOut"
            ]);

            Toastr::info(translate('you_can_either_add_the_seller_in_waiting_list_or_subscribe_to_a_payable_plan'), 'Warning',
            [
                "showDuration" => "300",
                "hideDuration" => "1000",
                "timeOut" => "20000",
                "extendedTimeOut" => "1000"
            ]);

            return back();
        }

        DB::transaction(function ($r) use ($request) {

            DB::table('seller_subscriptions')->where('seller_id', $request->seller_id)->update([
                'status' => false
            ]);

            // TO DO:
            // Consider waiting list for free plan

            $defaultTrialPlan = null;
            $defaultBrand = DEFAULT_BRAND;

            if(isset($request->is_vendor_exclusive_brand)){
                $defaultBrand = $request->brand_id;
            }

            if (isset($request->is_trial_plan)) {
                $defaultTrialPlan = TrialPlanSetting::first();
            }

            $planId = $defaultTrialPlan ? $defaultTrialPlan->plan_id : $request->subscription_plan;
            $billingTypeId = $defaultTrialPlan ? null : $request->billing_type;
            $currentEnd = $defaultTrialPlan ? Carbon::now()->addDays($defaultTrialPlan->duration_in_days) : Carbon::now()->addDays(BillingType::where('id', $billingTypeId)->first()?->duration_in_days);
            $isTrial = $defaultTrialPlan ? true : false;
            $isFree = $request->subscription_plan === 5 /* check if the plan is free */ ? true : false;

            $brandUpdate = Seller::where('id', $request->seller_id)
            ->update([
                'brand_id' => $defaultBrand == DEFAULT_BRAND ? : $request->brand_id,
            ]);

            $sellerSubscription = DB::table('seller_subscriptions')->insert([
                'seller_id' => $request->seller_id,
                'plan_id' => $planId,
                'billing_type_id' => $billingTypeId,
                'start_date' => SellerSubscription::where('seller_id', $request->seller_id)->get()->count() > 0 ? : now(),
                'current_start' => now(),
                'current_end' => $currentEnd,
                'is_free' => $isFree,
                'is_trial' => $isTrial,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        Toastr::success(translate('subscription_updated_successfully'));
        return back();

    }

    public function cancelSubscriptionPlan(Request $request){

        SellerSubscription::where('seller_id', $request->seller)
        ->update([
            'status' => 0,
        ]);

        Toastr::success(translate('subscription_deactivated_successfully'));
        return back();
    }

    public function subscriptionExpireList(Request $request){

        $expireDateRange = Carbon::now()->addDays(11);
        $today = Carbon::now();

        $filters = [
            'billing_id' => $request['billing_id'],
            'subscription_plan_id' => $request['subscription_plan_id'],
        ];

        $planExpireSellers = SellerSubscription::query();
        $planExpireSellers
        ->where('current_end', '<=', $expireDateRange)
        ->where('current_end', '>', $today)
        ->where('status', true);

        // Additional filters for subscription plan, billing type, and status
        if(isset($filters['subscription_plan_id'])){
            $planExpireSellers->when($filters['subscription_plan_id'], function ($query, $subscriptionPlanId) {
                $query->whereHas('plan', function ($query) use ($subscriptionPlanId) {
                    $query->where('plan_id', $subscriptionPlanId);
                });
            });
        }

        if(isset($filters['billing_id'])){
            $planExpireSellers->when($filters['billing_id'], function ($query, $billingId) {
                $query->whereHas('plan', function ($query) use ($billingId) {
                    $query->where('billing_type_id', $billingId);
                });
            });
        }

        $planExpireSellers = $planExpireSellers->paginate(getWebConfig(name: WebConfigKey::PAGINATION_LIMIT))->appends($filters);

        $planExpireSellers->getCollection()->transform(function ($subscription) {
            $currentEndDate = Carbon::parse($subscription->current_end);
            $daysLeft = Carbon::now()->diffInDays($currentEndDate);
            $subscription->days_left = $daysLeft;
            return $subscription;
        });

        $subscriptionPlans = SubscriptionPlan::all();
        $request->flash();

        return view('admin-views.seller.subscription-expire-list-index', compact('planExpireSellers', 'subscriptionPlans'));
    }

    public function package_selected(Request $request, $id, $seller_id)
    {
        $sellerId = $seller_id;
        $seller_subscription = SellerSubscription::where('seller_id', $sellerId)->with(['plan'])->latest()->first();
        $plan = SubscriptionPlan::where('id', $id)->first();
        $billingTypes = PlanBilling::where('plan_id', $plan?->id)->get();

        return response()->json([
            'view' => view('admin-views.seller.view._package_selected', compact('seller_subscription', 'plan', 'sellerId', 'billingTypes'))->render()
        ]);
    }

    public function package_renew_change_update(Request $request)
    {
        // dd($request->all());

        $package = SubscriptionPlan::findOrFail($request->plan_id);
        $seller = Seller::findOrFail($request->seller_id);
        $seller_id = $seller->id;

        $price = 0;
        $planBilling = PlanBilling::where(['billing_type_id' => $request->input('billing_type'), 'plan_id' => $package->id])->first();
        $price = $planBilling?->price;

        $total_price = $price;
        $reference = $request->reference ?? null;

        if ($request->button == 'renew') {
            $type = 'renew';
        } else {
            $type = null;
        }

        // If the selected plan is free plan and it is not renewing, which means we'll work on waiting list
        if($package?->slug == FREE_PLAN && $type != 'renew'){
            $availableSpot = $package?->available_vendors;
            $freePlanSubscribeCount = SellerSubscription::getFreePlanSubscribeCount();

            // put the seller in waiting list if chosen free plan, or return back with no available space
            if(isset($availableSpot) && $freePlanSubscribeCount > $availableSpot){

                $sellerExistsInWaitingList = SellerWaitingList::where('seller_id', $seller_id)->count();
                if($sellerExistsInWaitingList > 1){
                    Toastr::error(translate('the_seller_already_in_waiting_list_for_'. FREE_PLAN .'_plan'), '', ['timeOut' => 10000]);
                    Toastr::error(translate('either_upgrade_to_other_plan_or_let_the_seller_wait_for_available_space'), '', ['timeOut' => 10000]);
                    return back();
                }

                $latestPosition = SellerWaitingList::orderBy('id', 'desc')->first()?->position;

                SellerWaitingList::create([
                    'seller_id' => $seller_id,
                    'position' => $latestPosition ? $latestPosition + 1 : 1,
                ]);

                Toastr::error(translate('sorry_but_the_current_' . FREE_PLAN . '_plan_has_no_available_space_either_change_to_other_plan'), '', ['timeOut' => 10000]);
                Toastr::error(translate('the_seller_is_added_to_waiting_list'), '', ['timeOut' => 10000]);
                return back();
            }
        }

        if ($request->payment_type == 'wallet') {
            $wallet = SellerWallet::where('seller_id', $seller->id)->first();
            if ($wallet?->total_earning >= $total_price) {
                $payment_method = 'wallet';
                $status =  subscription_plan_chosen(request: $request, seller_id: $seller_id, package_id: $package->id, payment_method: $payment_method, reference: $reference, type: $type);

                if ($status === 'downgrade_error') {
                    Toastr::error(translate('subscription_update_failed'));
                    return back();
                }

                $wallet->withdrawn = $wallet?->withdrawn + $total_price;
                $wallet->total_earning = $wallet['total_earning'] - $total_price;
                $wallet?->save();
            } else {
                Toastr::error(translate('Insufficient_Wallet_Balance'));
                return back();
            }
        } elseif ($request->payment_type == 'pay_now') {
            $payment_method = 'manual_payment_admin';
            $status =  subscription_plan_chosen(request: $request, seller_id: $seller_id, package_id: $package->id, payment_method: $payment_method, reference: $reference, type: $type);
            if ($status === 'downgrade_error') {
                Toastr::error(translate('subscription_update_failed'));
                return back();
            }
        }

        // if a seller changes from free plan to other plan, there will be available space so that give a seller from waiting list the opportunity.
        $changeFromPlan = SubscriptionPlan::find($request->from_plan_id);
        if(($changeFromPlan && $changeFromPlan->slug == FREE_PLAN) && $type != 'renew'){
            $sellerWaitingId = SellerWaitingList::orderBy('id', 'asc')->pluck('seller_id')->first();
            $hasSellerActivePlan = SellerSubscription::where(['seller_id' => $sellerWaitingId, 'status' => 1])->count();

            // if seller has no active plan before, assign the seller the free plan here
            if($hasSellerActivePlan == 0){
                $payment_method = 'manual_payment_admin';
                $status =  subscription_plan_chosen(request: $request, seller_id: $sellerWaitingId, package_id: $request->from_plan_id, payment_method: $payment_method, reference: $reference, type: $type);

                if ($status) {
                    SellerWaitingList::where('seller_id', $sellerWaitingId)->delete();

                    // TODO: Implement notification for seller via mobile, SMS or email here that he got a free plan

                    // Notify the seller via email
                    $seller = Seller::find($sellerWaitingId);

                    if($seller){
                        try{
                            Mail::to($seller->email)->send(new FreePlanAssigned($seller));
                        }catch(\Exception $exception) {
                            info($exception);
                        }
                    }
                }
            }

        }

        Toastr::success(translate('subscription_successfully_saved'));
        return back();
    }
}
