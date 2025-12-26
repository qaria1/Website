<?php

namespace App\Http\Controllers\RestAPI\v3\seller\auth;

use App\Models\Shop;
use App\Models\Seller;
use App\Utils\Helpers;
use App\Utils\ImageManager;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email'         => 'required|unique:sellers',
            'shop_address'  => 'required',
            'f_name'        => 'required',
            'l_name'        => 'required',
            'shop_name'     => 'required',
            'phone'         => 'required|unique:sellers',
            'password'      => 'required|min:8',
            'image'         => 'required|mimes: jpg,jpeg,png,gif',
            'logo'          => 'required|mimes: jpg,jpeg,png,gif',
            'banner'        => 'required|mimes: jpg,jpeg,png,gif',
            'bottom_banner' => 'mimes: jpg,jpeg,png,gif',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => Helpers::error_processor($validator)], 403);
        }

        DB::beginTransaction();
        try {
            $seller = new Seller();
            $seller->f_name = $request->f_name;
            $seller->l_name = $request->l_name;
            $seller->phone = $request->phone;
            $seller->sex = $request->sex;
            $seller->age = $request->age;
            $seller->email = $request->email;

            // default brand_id is 1, which assumed is a normal seller or vendor
            $seller->brand_id = isset($request->is_vendor_exclusive_brand) ? $request->brand_id : DEFAULT_BRAND;

            $seller->image = ImageManager::upload('seller/', 'webp', $request->file('image'));
            $seller->password = bcrypt($request->password);
            $seller->status =  $request->status == 'approved'?'approved': "pending";

            // if($request['from_submit'] == 'admin'){
            //     $seller->checked = true;
            // }

            $seller->save();

            $shop = new Shop();
            $shop->seller_id = $seller->id;
            $shop->name = $request->shop_name;
            $shop->address = $request->shop_address;
            $shop->contact = $request->phone;
            $shop->image = ImageManager::upload('shop/', 'webp', $request->file('logo'));
            $shop->banner = ImageManager::upload('shop/banner/', 'webp', $request->file('banner'));
            $shop->bottom_banner = ImageManager::upload('shop/banner/', 'webp', $request->file('bottom_banner'));
            $shop->save();

            // fixing chat feature
            \App\Models\Chatting::create([
                'seller_id' => $seller->id,
                'admin_id' => 0,
                'message' => 'Hey ' . $seller->f_name . ' ' . $seller->l_name . ', Welcome to ' . env('APP_NAME') ?? ' our Platform!',
                'sent_by_admin' => 1,
                'shop_id' => $shop->id
            ]);

            DB::table('seller_wallets')->insert([
                'seller_id' => $seller['id'],
                'withdrawn' => 0,
                'commission_given' => 0,
                'total_earning' => 0,
                'pending_withdraw' => 0,
                'delivery_charge_earned' => 0,
                'collected_cash' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // subscription plan related code here
            $defaultTrialPlan = null;

            if (!isset($request->subscription_plan) || $request->from == 'seller' || isset($request->is_trial_plan)) {
                $trialPeriodExists = \App\Models\BusinessSetting::where('type', 'trial_period')->get();
                if($trialPeriodExists->count() < 1){

                    $tonaPlan = \App\Models\SubscriptionPlan::where('slug', 'tona')->first();
                    \App\Models\BusinessSetting::create([
                        'type' => 'trial_period',
                        'value' => '{"plan_id":"'. $tonaPlan->id ?? 3 . '","validity":"60","price":"500","status":1}',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                $defaultTrialPlan = \App\Models\BusinessSetting::where('type', 'trial_period')->first()?->toArray();
                $defaultTrialPlan['values'] = json_decode($defaultTrialPlan['value']);
            }

            $planId = $defaultTrialPlan ? $defaultTrialPlan['values']?->plan_id : $request->subscription_plan;
            $billingTypeId = $defaultTrialPlan ? null : $request->billing_type;
            $currentEnd = $defaultTrialPlan ? \Carbon\Carbon::now()->addDays($defaultTrialPlan['values']?->validity) : \Carbon\Carbon::now()->addDays(\App\Models\BillingType::where('id', $billingTypeId)->first()?->duration_in_days);
            $isTrial = $defaultTrialPlan ? true : false;

            $plan = $request->subscription_plan ? \App\Models\SubscriptionPlan::find($request->subscription_plan) : null;
            $isFree = $plan && $plan->slug == FREE_PLAN /* check if the plan is free */ ? true : false;

            $price = 0;
            $validity = 0;

            $planBilling = \App\Models\PlanBilling::where('plan_id', $planId)->first();
            $price = $isTrial ? 0 : $planBilling?->price;
            $validity = $defaultTrialPlan ? $defaultTrialPlan['values']?->validity : $planBilling?->validity;

            $subscriptionPlan = \App\Models\SubscriptionPlan::find($planId);

            // Seller Subscription Insertion
            $sellerSubscription = DB::table('seller_subscriptions')->insert([
                'seller_id' => $seller['id'],
                'plan_id' => $planId,
                'billing_type_id' => $billingTypeId,

                'price' => $price,
                'validity' => $validity,
                'max_product_lifecycle' => $subscriptionPlan?->max_product_lifecycle,
                'max_product_upload' => $subscriptionPlan?->max_product_upload,

                'start_date' => \App\Models\SellerSubscription::where('seller_id', $seller['id'])->get()->count() > 0 ?: now(),
                'current_start' => now(),
                'current_end' => $currentEnd,
                'is_free' => $isFree,
                'is_trial' => $isTrial,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Subscription Transaction Information
            $subscription_transaction= new \App\Models\SubscriptionTransaction();
            $subscription_transaction_ID= Str::uuid();
            $subscription_transaction->id=  $subscription_transaction_ID;

            $subscription_transaction->seller_id = $seller['id'];
            $subscription_transaction->plan_id = $planId;

            $subscription_transaction->price = $price;
            $subscription_transaction->validity = $validity;
            $subscription_transaction->paid_amount = $price;
            $subscription_transaction->package_details = [
                'max_product_lifecycle' => $subscriptionPlan?->max_product_lifecycle,
                'max_product_upload' => $subscriptionPlan?->max_product_upload,
                'discount' => $subscriptionPlan?->discount,
                'product_top_search' => $subscriptionPlan?->product_top_search,
                'item_verification'=> $subscriptionPlan?->item_verification,
                'product_photoshoot'=> $subscriptionPlan?->product_photoshoot,
                'free_delivery'=> $subscriptionPlan?->free_delivery,
                'available_vendors' => $subscriptionPlan?->available_vendors ?? ''
            ];
            $subscription_transaction->created_by = 'seller';
            $subscription_transaction->created_at = now();
            $subscription_transaction->updated_at = now();

            $subscription_transaction->save();

            DB::commit();
            return response()->json(['message' => 'Shop apply successfully!'], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Shop apply fail!'], 403);
        }

    }
}
