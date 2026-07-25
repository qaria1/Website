<?php

namespace App\Http\Controllers\Admin\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Seller;
use Illuminate\Http\Request;

class ExclusiveBrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Shop::active()->with(['seller' => function ($q) {
            $q->select(['id', 'f_name', 'l_name', 'email', 'phone', 'image', 'brand_id']);
        }])->withCount(['products' => function ($q) {
            $q->active();
        }]);

        if ($request->has('searchValue') && $request['searchValue'] != null) {
            $keys = explode(' ', $request['searchValue']);
            $query->where(function ($q) use ($keys) {
                foreach ($keys as $key) {
                    $q->orWhere('name', 'like', '%' . $key . '%');
                }
            });
        }

        if ($request->has('filter') && $request['filter'] == 'exclusive') {
            $query->exclusiveBrand();
        }

        $shops = $query->latest()->paginate(getWebConfig(name: 'pagination_limit') ?: 25)->appends([
            'searchValue' => $request['searchValue'],
            'filter' => $request['filter'],
        ]);

        return view('admin-views.exclusive-brand.list', compact('shops'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'is_exclusive_brand' => 'required|in:0,1',
        ]);

        $shop = Shop::active()->find($request['id']);
        if (!$shop) {
            return response()->json(['error' => translate('shop_not_found')], 404);
        }

        $shop->is_exclusive_brand = (bool) $request['is_exclusive_brand'];
        $shop->save();

        return response()->json([
            'success' => true,
            'message' => $request['is_exclusive_brand']
                ? translate('shop_added_to_exclusive_brands')
                : translate('shop_removed_from_exclusive_brands'),
        ]);
    }
}
