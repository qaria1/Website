<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Enums\ViewPaths\Admin\BusinessSettings;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\SellerSettingsRequest;
use App\Models\CommissionRule;
use App\Models\Seller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SellerSettingsController extends BaseController
{

    public function __construct(
        private readonly BusinessSettingRepositoryInterface $businessSettingRepo,
    ) {}

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View Index function is the starting point of a controller
     * Index function is the starting point of a controller
     */
    public function index(Request|null $request, string $type = null): View
    {
        return $this->getView();
    }

    public function getView(): View
    {
        $sales_commission = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'sales_commission']);
        if (!isset($sales_commission)) {
            $this->businessSettingRepo->add(data: ['type' => 'sales_commission', 'value' => 0]);
        }

        $seller_registration = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'seller_registration']);
        if (!isset($seller_registration)) {
            $this->businessSettingRepo->add(data: ['type' => 'seller_registration', 'value' => 1]);
        }
        $vendors =  Seller::get();
        $rules = CommissionRule::get();
        return view(BusinessSettings::SELLER_VIEW[VIEW], compact('vendors', 'rules'));
    }

    public function update(SellerSettingsRequest $request): RedirectResponse
    {
        $this->businessSettingRepo->updateOrInsert(type: 'sales_commission', value: $request->get('commission', 0));
        $this->businessSettingRepo->updateOrInsert(type: 'seller_pos', value: $request->get('seller_pos', 0));
        $this->businessSettingRepo->updateOrInsert(type: 'seller_registration', value: $request->get('seller_registration', 0));
        $this->businessSettingRepo->updateOrInsert(type: 'minimum_order_amount_by_seller', value: $request->get('minimum_order_amount_by_seller', 0));
        $this->businessSettingRepo->updateOrInsert(type: 'new_product_approval', value: $request->get('new_product_approval', 0));
        $this->businessSettingRepo->updateOrInsert(type: 'product_wise_shipping_cost_approval', value: $request->get('product_wise_shipping_cost_approval', 0));
        Toastr::success(translate('Updated_successfully'));
        return redirect()->back();
    }
    public function commissionRulesUpdate(Request $request)
    {
        $request->validate([
            'seller_id' => 'nullable|exists:sellers,id',
            'min_price' => 'required|numeric|min:0',
            'max_price' => 'nullable|numeric|gte:min_price',
            'commission_percent' => 'required|numeric|min:0|max:100',
        ]);
        $min = $request->min_price;
        $max = $request->max_price;
        $commission = $request->commission_percent;
        $exists = CommissionRule::where('seller_id', $request->seller_id)
            ->where(function ($q) use ($request) {
                $q->whereBetween('min_price', [$request->min_price, $request->max_price])
                    ->orWhereBetween('max_price', [$request->min_price, $request->max_price]);
            })
            ->exists();

        if ($exists) {
            return back()->withErrors(['overlap' => 'This price range overlaps with an existing rule']);
        }
        $duplicate = CommissionRule::where('min_price', $min)
            ->where(function ($q) use ($max) {
                if ($max === null) {
                    $q->whereNull('max_price');
                } else {
                    $q->where('max_price', $max);
                }
            })
            ->where('commission_percent', $commission)
            ->exists();

        if ($duplicate) {
            return back()->withErrors([
                'duplicate' => 'A commission rule with the same range and percentage already exists.'
            ]);
        }

        CommissionRule::create([
            'seller_id' => $request->seller_id,
            'min_price' => $request->min_price,
            'max_price' => $request->max_price,
            'commission_percent' => $request->commission_percent,
        ]);
        Toastr::success(translate('Created_successfully'));
        return redirect()->back();
    }
    public function deleteCommissionRule($id)
    {
        $rule = CommissionRule::find($id);
        $rule->delete();
        Toastr::success(translate('Deleted_successfully'));
        return redirect()->back();
    }
    public function updateCommissionRule(Request $request, $id)
    {
        $request->validate([
            'seller_id' => 'nullable|exists:sellers,id',
            'min_price' => 'required|numeric|min:0',
            'max_price' => 'nullable|numeric|gte:min_price',
            'commission_percent' => 'required|numeric|min:0|max:100',
        ]);
        $rule = CommissionRule::find($id);
        $rule->min_price = $request->min_price;
        $rule->max_price = $request->max_price;
        $rule->commission_percent = $request->commission_percent;
        $rule->save();
        Toastr::success(translate('Updated_successfully'));
        return redirect()->back();
    }
}
