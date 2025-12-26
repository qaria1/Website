<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\CategoryShippingCostRepositoryInterface;
use App\Contracts\Repositories\ShippingMethodRepositoryInterface;
use App\Contracts\Repositories\ShippingTypeRepositoryInterface;
use App\Http\Controllers\BaseController;
use App\Models\BillingType;
use App\Models\BusinessSetting;
use App\Models\SubscriptionPlan;
use App\Models\TrialPlanSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Services\CategoryShippingCostService;
use App\Services\ShippingMethodService;
use Illuminate\Support\Facades\DB;

class SubscriptionSettingController extends BaseController
{

    public function __construct()
    {
    }

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View Index function is the starting point of a controller
     * Index function is the starting point of a controller
     */
    public function index(Request|null $request, string $type = null): View
    {
        $subscriptionPlans = SubscriptionPlan::with('billingTypes')->get();

        $defaultTrialPlan = BusinessSetting::where('type', 'trial_period')->first()->toArray();
        $defaultTrialPlan['values'] = json_decode($defaultTrialPlan['value']);

        return view('admin-views.subscription.index', compact('subscriptionPlans', 'defaultTrialPlan'));
    }

    public function getUpdateView(string|int $id): View|RedirectResponse
    {
        $plan = SubscriptionPlan::findOrFail($id);
        return view('admin-views.subscription.update-view', compact('plan'));
    }

    public function update(Request $request, $id): RedirectResponse
    {

        $subscriptionPlan = SubscriptionPlan::find($id);

        $subscriptionPlan?->update([
                'name' => $request->input('plan_name'),
                'code' => $request->input('plan_code'),
                'max_product_lifecycle' => $request->input('max_product_lifecycle'),
                'max_product_upload' => $request->input('max_product_upload'),
                'available_vendors' => $request->input('available_vendors'),
        ]);

        // Sync features with values in the pivot table
        // $features = $request->input('features', []);
        // $featuresData = [];

        // foreach ($features as $featureId => $value) {
        //     $featuresData[$featureId] = ['value' => $value];
        // }

        // $subscriptionPlan->features()->sync($featuresData);

        Toastr::success(translate('subscription_updated_successfully'));
        return back();
    }

    public function updateTrialPlan(Request $request)
    {

        $saveTrialPlanSetting = BusinessSetting::where('type', 'trial_period')->update([
            'value' => '{"plan_id":"'.$request->input('subscription_plan').'","validity":"'.$request->input('plan_duration').'","price":"500","status":1}',
            'updated_at' => now()
        ]);

        if ($saveTrialPlanSetting) {
            Toastr::success(translate('trial_subscription_updated_successfully'));
            return back();
        }
    }

    public function assignBillingType($id)
    {

        $plan = SubscriptionPlan::findOrFail($id);

        $assignedBillingTypes = DB::table('billing_types')
            ->join('plan_billings', 'billing_types.id', '=', 'plan_billings.billing_type_id')
            ->where('plan_billings.plan_id', '=', $id)->select('billing_types.*', 'plan_billings.price')->get();

        $assignedBillingTypeList = [];

        if (!empty($assignedBillingTypes)) {
            foreach ($assignedBillingTypes as $billing) {
                array_push($assignedBillingTypeList, $billing->id);
            }
        }

        $billingTypeArray = BillingType::whereNotIn('id', $assignedBillingTypeList)->get();

        return view('admin-views.subscription.add-billing-type', compact('assignedBillingTypes', 'billingTypeArray', 'plan', 'billingTypeArray'));
    }

    public function storeBillingType(Request $request)
    {

        $data = $request->input();
        $planId = $data['plan_id'];
        $billingType = $data['billing_type'];
        $billingPrice = $data['billing_price'];

        $billingTypeData = BillingType::where('id', $billingType)->first();
        $validity = $billingTypeData?->duration_in_days;

        $planBilling = DB::insert('insert into plan_billings (price, validity, plan_id, billing_type_id) values (?, ?, ?, ?)', [$billingPrice, $validity, $planId, $billingType]);

        Toastr::success(translate('billing_type_added_successfully'));
        return back();
    }
}
