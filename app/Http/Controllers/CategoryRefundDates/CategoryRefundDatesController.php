<?php

namespace App\Http\Controllers\CategoryRefundDates;

use Illuminate\Http\Request;
use App\Models\CategoryRefundDate;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;


class CategoryRefundDatesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // //


        $paginationLimit = getWebConfig('pagination_limit');
        $categoryRefundDates = CategoryRefundDate::paginate($paginationLimit);
        $languages = getWebConfig('pnc_language') ?? null;
        $defaultLanguage = $languages[0];

        // dd($delivery_classes);
        return view('admin-views.category-refund-dates.view', [
            'categoryRefundDates' => $categoryRefundDates,
            'languages' => $languages,
            'defaultLanguage' => $defaultLanguage,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('category-refund-dates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $number_of_days = $request->name[0];
        $number_of_days = (int) $number_of_days;
        $categoryRefundDate = new CategoryRefundDate();
        $categoryRefundDate->number_of_days = $number_of_days;
        $categoryRefundDate->save();

        Toastr::success(translate('refund_date_added_successfully'));
        return back();

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CategoryRefundDate $categoryRefundDate)
    {
        //
        // dd($categoryRefundDate);
        $languages = getWebConfig('pnc_language') ?? null;
        $defaultLanguage = $languages[0];

        return view('admin-views.category-refund-dates.edit', [
            'categoryRefundDate' => $categoryRefundDate,
            'languages' => $languages,
            'defaultLanguage' => $defaultLanguage,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //

        // dd($request);
        // dd($id);
        // dd($request->input('number_of_days'));
        $categoryRefundDate = CategoryRefundDate::findOrFail($id);
        $categoryRefundDate->number_of_days = $request->input('number_of_days', 0);
        $categoryRefundDate->save();

        Toastr::success(translate('refund_date_updated_successfully'));
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $categoryRefundDate = CategoryRefundDate::findOrFail($id);
        $categoryRefundDate->delete();

        return redirect()->route('category-refund-dates.index');
    }
}
