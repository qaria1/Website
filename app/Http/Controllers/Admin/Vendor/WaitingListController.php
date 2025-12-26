<?php

namespace App\Http\Controllers\Admin\Vendor;

use App\Models\Seller;
use App\Enums\WebConfigKey;
use Illuminate\Http\Request;
use App\Models\SellerWaitingList;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\BaseController;

class WaitingListController extends BaseController
{
    public function __construct()
    {
    }

    public function index(Request|null $request, string $type = null): View
    {
        return view();
    }

    public function waitingList(Request $request) {
        $waitingSellers = SellerWaitingList::orderBy('position', 'asc')
        ->paginate(getWebConfig(name: WebConfigKey::PAGINATION_LIMIT));
        return view('admin-views.seller.waiting-list-index', compact('waitingSellers'));
    }

    public function createWaitingList()
    {
        $allSellers = Seller::where('status', 'approved')->get();
        return view('admin-views.seller.waiting-list-create', compact('allSellers'));
    }

    public function storeWaitingList(Request $request) {

        $latestPosition = SellerWaitingList::orderBy('id', 'desc')->first()?->position;

        $waitingList = SellerWaitingList::create([
            'seller_id' => $request->seller,
            'position' => $latestPosition ? $latestPosition + 1 : 1,
        ]);

        Toastr::success(translate('vendor_has_been_added_to_waiting_list'));
        return redirect()->route('admin.sellers.waiting-list.index');
    }

    public function removeWaitingList(Request $request) {

        $removeFromWaitingList = SellerWaitingList::where('id', $request->list_id)->delete();

        Toastr::success(translate('vendor_has_been_removed_from_waiting_list'));
        return redirect()->route('admin.sellers.waiting-list.index');
    }
}
