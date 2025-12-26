<?php

namespace App\Http\Controllers\Vendor\Auth;

use App\Models\Shop;
use App\Models\Seller;
use App\Utils\Helpers;
use App\Enums\SessionKey;
use App\Models\BillingType;
use App\Models\PlanBilling;
use App\Utils\ImageManager;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\BusinessSetting;
use App\Models\SubscriptionPlan;
use App\Models\TrialPlanSetting;
use App\Models\SellerSubscription;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use SebastianBergmann\Type\NullType;
use App\Models\SubscriptionTransaction;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    public function create()
    {
        $business_mode = Helpers::get_business_settings('business_mode');
        $seller_registration = Helpers::get_business_settings('seller_registration');
        if ((isset($business_mode) && $business_mode == 'single') || (isset($seller_registration) && $seller_registration == 0)) {
            Toastr::warning(translate('access_denied!!'));
            return redirect('/');
        }

        $subscriptionPlans = DB::table('subscription_plans')->select()->get();
        $defaultTrialPlan = TrialPlanSetting::first();

        return view(VIEW_FILE_NAMES['seller_registration'], compact('subscriptionPlans', 'defaultTrialPlan'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate(
            [
                'image'             => 'required|mimes: jpg,jpeg,png,gif',
                'logo'              => 'required|mimes: jpg,jpeg,png,gif',
                'banner'            => 'required|mimes: jpg,jpeg,png,gif',
                'bottom_banner'     => 'mimes: jpg,jpeg,png,gif',
                'email'             => 'required|unique:sellers',
                'shop_address'      => 'required',
                'f_name'            => 'required',
                'l_name'            => 'required',
                'shop_name'         => 'required',
                'phone'             => 'required|unique:sellers',
                'password'          => 'required|same:confirm_password|min:8',
                'confirm_password'  => 'required',
            ],
            [
                'image.required' => translate('image_is_required') . '!',
                'logo.required' => translate('logo_name_is_required') . '!',
                'banner.required' => translate('banner_name_is_required') . '!',
                'bottom_banner.required' => translate('bottom_banner_name_is_required') . '!',
                'shop_address.required' => translate('shop_address_is_required') . '!',
                'password.required'            => translate('password_is_required') . '!',
                'password.confirmed'           => translate('password_confirmation_mismatch') . '!',
                'confirm_password.required'    => translate('confirm_password_is_required') . '!',
            ]
        );

        $request->flash();

        if ($request['from_submit'] != 'admin') {
            $recaptcha = Helpers::get_business_settings('recaptcha');
            if (isset($recaptcha) && $recaptcha['status'] == 1) {
                try {
                    $request->validate([
                        'g-recaptcha-response' => [
                            function ($attribute, $value, $fail) {
                                $secret_key = Helpers::get_business_settings('recaptcha')['secret_key'];
                                $response = $value;
                                $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $response;
                                $response = \file_get_contents($url);
                                $response = json_decode($response);
                                if (!$response->success) {
                                    $fail(translate('ReCAPTCHA Failed'));
                                }
                            },
                        ],
                    ]);
                } catch (\Exception $exception) {
                }
            } else {
                if (strtolower($request->default_recaptcha_id_seller_regi) != strtolower(Session(SessionKey::VENDOR_RECAPTCHA_KEY))) {
                    Session::forget('default_recaptcha_id_seller_regi');
                    return back()->withErrors(translate('Captcha_Failed'));
                }
            }
        }

        /*

            Tables that are affected at seller registration
            - seller_subscriptions: the real subscription with dates and other information
            - seller_product_counts: product count information for the subscription made in seller_subscriptions table

        */

        DB::transaction(function ($r) use ($request) {
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
            $seller->status =  $request->status == 'approved' ? 'approved' : "pending";

            if($request['from_submit'] == 'admin'){
                $seller->checked = true;
            }

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

            if ($request->from == 'seller' || isset($request->is_trial_plan)) {

                $trialPeriodExists = BusinessSetting::where('type', 'trial_period')->get();
                if($trialPeriodExists->count() < 1){

                    $tonaPlan = SubscriptionPlan::where('slug', 'tona')->first();
                    BusinessSetting::create([
                        'type' => 'trial_period',
                        'value' => '{"plan_id":"'. $tonaPlan->id ?? 3 . '","validity":"60","price":"500","status":1}',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                $defaultTrialPlan = BusinessSetting::where('type', 'trial_period')->first()?->toArray();
                $defaultTrialPlan['values'] = json_decode($defaultTrialPlan['value']);
            }

            $planId = $defaultTrialPlan ? $defaultTrialPlan['values']?->plan_id : $request->subscription_plan;
            $billingTypeId = $defaultTrialPlan ? null : $request->billing_type;
            $currentEnd = $defaultTrialPlan ? Carbon::now()->addDays($defaultTrialPlan['values']?->validity) : Carbon::now()->addDays(BillingType::where('id', $billingTypeId)->first()?->duration_in_days);
            $isTrial = $defaultTrialPlan ? true : false;

            $plan = $request->subscription_plan ? SubscriptionPlan::find($request->subscription_plan) : null;
            $isFree = $plan && $plan->slug == FREE_PLAN /* check if the plan is free */ ? true : false;

            $price = 0;
            $validity = 0;

            $planBilling = PlanBilling::where('plan_id', $planId)->first();
            $price = $isTrial ? 0 : $planBilling?->price;
            $validity = $defaultTrialPlan ? $defaultTrialPlan['values']?->validity : $planBilling?->validity;

            $subscriptionPlan = SubscriptionPlan::find($planId);

            // Seller Subscription Insertion
            $sellerSubscription = DB::table('seller_subscriptions')->insert([
                'seller_id' => $seller['id'],
                'plan_id' => $planId,
                'billing_type_id' => $billingTypeId,

                'price' => $price,
                'validity' => $validity,
                'max_product_lifecycle' => $subscriptionPlan?->max_product_lifecycle,
                'max_product_upload' => $subscriptionPlan?->max_product_upload,

                'start_date' => SellerSubscription::where('seller_id', $seller['id'])->get()->count() > 0 ?: now(),
                'current_start' => now(),
                'current_end' => $currentEnd,
                'is_free' => $isFree,
                'is_trial' => $isTrial,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Subscription Transaction Information
            $subscription_transaction= new SubscriptionTransaction();
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
            $subscription_transaction->created_by = 'admin';
            $subscription_transaction->created_at = now();
            $subscription_transaction->updated_at = now();

            $subscription_transaction->save();

        });

        if ($request['from_submit'] == 'seller') {
            Toastr::success(translate('successfully_registered'), '', ['timeOut' => 10000]);
            Toastr::success(translate('wait_for_your_request_to_be_approved'), '', ['timeOut' => 10000]);
            return redirect()->route('home');
        }

        if ($request->status == 'approved') {
            Toastr::success(translate('vendor_registered_successfully'));
            return back();
        } else {
            Toastr::success(translate('vendor_registered_successfully'));
            return redirect()->route('vendor.auth.login');
        }
    }
}
