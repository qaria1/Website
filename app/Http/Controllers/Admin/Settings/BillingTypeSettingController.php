<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\BaseController;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillingTypeSettingController extends BaseController
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
        $billingTypes = DB::table('billing_types')->orderBy('id', 'DESC')->get();

        return view('admin-views.billing-type.index', compact('billingTypes'));
    }

    public function getUpdateView(string|int $id): View|RedirectResponse
    {
        $billingType = DB::table('billing_types')->where('id', $id)->first();
        return view('admin-views.billing-type.update-view', compact('billingType'));
    }

    public function update(Request $request): RedirectResponse
    {
        $updateBillingType = DB::table('billing_types')
            ->where('id', $request->input('billing_id'))
            ->update([
                'name' => $request->input('billing_name'),
                // 'description' => $request->input('description'),
                'duration_in_days' => $request->input('billing_duration'),
            ]);

        Toastr::success(translate('billing_type_updated_successfully'));
        return redirect()->route('admin.business-settings.billing-type.index');
    }

    public function addBillingType(Request $request)
    {

        $newBillingType = DB::table('billing_types')->insert([
            'name' => $request->billing_name,
            'duration_in_days' => $request->billing_duration,
            // 'description' => $request->description
        ]);

        Toastr::success(translate('billing_type_added_successfully'));
        return back();
    }
}
