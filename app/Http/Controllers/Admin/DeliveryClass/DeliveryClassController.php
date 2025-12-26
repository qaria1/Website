<?php

namespace App\Http\Controllers\Admin\DeliveryClass;

use Illuminate\Http\Request;
use App\Models\DeliveryClass;
use App\Services\CategoryService;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Controllers\BaseController;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\TranslationRepositoryInterface;


// use App\Http\Controllers\BaseController;


class DeliveryClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function __construct(
        private readonly TranslationRepositoryInterface $translationRepo,
    ) {
    }

    public function index()
    {
        $paginationLimit = getWebConfig('pagination_limit');
        $delivery_classes = DeliveryClass::paginate($paginationLimit);
        $languages = getWebConfig('pnc_language') ?? null;
        $defaultLanguage = $languages[0];

        // dd($delivery_classes);
        return view('admin-views.delivery-class.view', [
            'delivery_classes' => $delivery_classes,
            'languages' => $languages,
            'defaultLanguage' => $defaultLanguage,
        ]);
    }


    // public function index()
    // {
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        dd($request);
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request, CategoryService $categoryService)
    {
        //
        // dd($request);
        // dd($request->name[0]);

        // dd($request);
        $dataArray = $categoryService->deliveryGetAddData(request: $request);
        // dd($dataArray);

        // $savedCategory = $this->categoryRepo->add(data: $dataArray);// create a new delivery class with the given data array

        // Create a new DeliveryClass instance
        // dd($dataArray);
        $deliveryClass = new DeliveryClass();

        // Assign values from the request
        $deliveryClass->name = $dataArray['name'];
        $deliveryClass->code = $dataArray['code'];
        $deliveryClass->description = $dataArray['description'];

        // Save the DeliveryClass instance to the database
        $deliveryClass->save();

        $this->translationRepo->add(request: $request, model: 'App\Models\DeliveryClass', id: $deliveryClass->id);

        Toastr::success(translate('delivery_class_added_successfully'));
        return back();

    }


    /**
     * Display the specified resource.
     */
    public function show(DeliveryClass $deliveryClass)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DeliveryClass $deliveryClass)
    {
        $languages = getWebConfig('pnc_language') ?? null;
        $defaultLanguage = $languages[0];

        return view('admin-views.delivery-class.edit', [
            'deliveryClass' => $deliveryClass,
            'languages' => $languages,
            'defaultLanguage' => $defaultLanguage,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DeliveryClass $deliveryClass, CategoryService $categoryService)
    {

        $dataArray = $categoryService->deliveryGetAddData(request: $request);
        $deliveryClass->where('id', $deliveryClass->id)->update($dataArray);

        $this->translationRepo->add(request: $request, model: 'App\Models\DeliveryClass', id: $deliveryClass->id);

        Toastr::success(translate('delivery_class_updated_successfully'));
        return redirect()->route('admin.delivery-class.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeliveryClass $deliveryClass)
    {
        //
    }
}
