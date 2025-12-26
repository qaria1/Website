@extends('layouts.back-end.app')

@section('title', $seller?->shop->name ?? translate('shop_name_not_found'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{ asset('/public/assets/back-end/img/add-new-seller.png') }}" alt="">
                {{ translate('vendor_details') }}
            </h2>
        </div>
        <div class="flex-between d-sm-flex row align-items-center justify-content-between mb-2 mx-1">
            <div>
                @if ($seller->status == 'pending')
                    <div class="mt-4">
                        <div class="flex-start">
                            <div class="mx-1">
                                <h4><i class="tio-shop-outlined"></i></h4>
                            </div>
                            <div>{{ translate('vendor_request_for_open_a_shop') }}</div>
                        </div>
                        <div class="text-center">
                            <form class="d-inline-block" action="{{ route('admin.sellers.updateStatus') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $seller->id }}">
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn--primary btn-sm">{{ translate('approve') }}</button>
                            </form>
                            <form class="d-inline-block" action="{{ route('admin.sellers.updateStatus') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $seller->id }}">
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="btn btn-danger btn-sm">{{ translate('reject') }}</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="page-header">
            <div class="flex-between row mx-1">
                <div>
                    <h1 class="page-header-title">
                        {{ $seller?->shop->name ?? translate('shop_Name') . ' : ' . translate('update_Please') }}</h1>
                </div>
            </div>
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <ul class="nav nav-tabs flex-wrap page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link "
                            href="{{ route('admin.sellers.view', $seller->id) }}">{{ translate('shop') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ route('admin.sellers.view', ['id' => $seller->id, 'tab' => 'order']) }}">{{ translate('order') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active"
                            href="{{ route('admin.sellers.view', ['id' => $seller->id, 'tab' => 'product']) }}">{{ translate('product') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ route('admin.sellers.view', ['id' => $seller->id, 'tab' => 'setting']) }}">{{ translate('setting') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ route('admin.sellers.view', ['id' => $seller->id, 'tab' => 'transaction']) }}">{{ translate('transaction') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ route('admin.sellers.view', ['id' => $seller->id, 'tab' => 'review']) }}">{{ translate('review') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ route('admin.sellers.view', ['id' => $seller->id, 'tab' => 'subscription']) }}">{{ translate('subscription') }}</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="product">
                <div class="row pt-2">
                    <div class="col-md-12">
                        <div class="card h-100">
                            <div class="px-3 py-4">
                                <h5 class="mb-0 d-flex align-items-center gap-2">
                                    {{ translate('products') }}
                                    <span class="badge badge-soft-dark radius-50 fz-12">{{ $products->total() }}</span>
                                </h5>
                            </div>

                            <div class="table-responsive datatable-custom">
                                <table id="columnSearchDatatable"
                                    style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};"
                                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                    <thead class="thead-light thead-50 text-capitalize">
                                        <tr>
                                            <th>{{ translate('SL') }}</th>
                                            <th>{{ translate('product Name') }}</th>
                                            <th>{{ translate('purchase_price') }}</th>
                                            <th>{{ translate('selling_price') }}</th>
                                            <th class="text-center">{{ translate('featured') }}</th>
                                            <th class="text-center">{{ translate('active_status') }}</th>
                                            <th class="text-center">{{ translate('deal_of_the_day') }}</th>
                                            <th class="text-center">{{ translate('action') }}</th>
                                        </tr>
                                    </thead>

                                    <tbody id="set-rows">
                                        @foreach ($products as $k => $product)
                                            <tr>
                                                <td>{{ $products->firstItem() + $k }}</td>
                                                <td>
                                                    <a href="{{ route('admin.products.view', [$product['id']]) }}"
                                                        class="title-color hover-c1">
                                                        {{ substr($product['name'], 0, 20) }}{{ strlen($product['name']) > 20 ? '...' : '' }}
                                                    </a>
                                                </td>
                                                <td>
                                                    {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $product['purchase_price'])) }}
                                                </td>
                                                <td>
                                                    {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $product['unit_price'])) }}
                                                </td>
                                                <td>
                                                    @php($product_name = str_replace("'", '`', $product['name']))
                                                    <form action="{{ route('admin.products.featured-status') }}"
                                                        method="post" id="product-featured{{ $product['id'] }}-form"
                                                        data-from="featured-product-status">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $product['id'] }}">
                                                        <label class="switcher mx-auto">
                                                            <input type="checkbox"
                                                                class="switcher_input toggle-switch-message"
                                                                id="product-featured{{ $product['id'] }}" name="status"
                                                                value="1"
                                                                {{ $product['featured'] == 1 ? 'checked' : '' }}
                                                                data-modal-id = "toggle-status-modal"
                                                                data-toggle-id = "product-featured{{ $product['id'] }}"
                                                                data-on-image = "product-status-on.png"
                                                                data-off-image = "product-status-off.png"
                                                                data-on-title = "{{ translate('Want_to_Add') . ' ' . $product_name . ' ' . translate('to_the_featured_section') . '?' }}"
                                                                data-off-title = "{{ translate('Want_to_Remove') . ' ' . $product_name . ' ' . translate('to_the_featured_section') . '?' }}"
                                                                data-on-message = "<p>{{ translate('if_enabled_this_product_will_be_shown_in_the_featured_product_on_the_website_and_customer_app') }}</p>"
                                                                data-off-message = "<p>{{ translate('if_disabled_this_product_will_be_removed_from_the_featured_product_section_of_the_website_and_customer_app') }}</p>">`)">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </form>
                                                </td>
                                                <td>
                                                    <form action="{{ route('admin.products.status-update') }}"
                                                        method="post" id="product-status{{ $product['id'] }}-form"
                                                        data-from="product-status-update">
                                                        @csrf
                                                        <input type="hidden" name="id"
                                                            value="{{ $product['id'] }}">
                                                        <label class="switcher mx-auto">
                                                            <input type="checkbox"
                                                                class="switcher_input toggle-switch-message"
                                                                id="product-status{{ $product['id'] }}" name="status"
                                                                value="1"
                                                                {{ $product['status'] == 1 ? 'checked' : '' }}
                                                                data-modal-id = "toggle-status-modal"
                                                                data-toggle-id = "product-status{{ $product['id'] }}"
                                                                data-on-image = "product-status-on.png"
                                                                data-off-image = "product-status-off.png"
                                                                data-on-title = "{{ translate('Want_to_Turn_ON') . ' ' . $product_name . ' ' . translate('status') . '?' }}"
                                                                data-off-title = "{{ translate('Want_to_Turn_OFF') . ' ' . $product_name . ' ' . translate('status') . '?' }}"
                                                                data-on-message = "<p>{{ translate('if_enabled_this_product_will_be_available_on_the_website_and_customer_app') }}</p>"
                                                                data-off-message = "<p>{{ translate('if_disabled_this_product_will_be_hidden_from_the_website_and_customer_app') }}</p>">`)">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </form>
                                                </td>
                                                <td>
                                                    <?php
                                                    $dealData = collect($deals)['data'];
                                                    
                                                    // Build lookup arrays only once
                                                    $dealProductIds = array_column($dealData, 'product_id');
                                                    $dealIds = array_column($dealData, 'id');
                                                    $dealStatuses = array_column($dealData, 'status');
                                                    
                                                    // Locate deal by product_id
                                                    $index = array_search($product['id'], $dealProductIds);
                                                    
                                                    $matchedDealId = $index !== false ? $dealIds[$index] : null;
                                                    $matchedDealStatus = $index !== false ? $dealStatuses[$index] : 0;
                                                    ?>

                                                    @if ($matchedDealId)
                                                        {{-- Deal exists → render form --}}
                                                        <form action="{{ route('admin.deal.day-status-update') }}"
                                                            method="post" id="deal-of-the-day{{ $matchedDealId }}-form"
                                                            data-from="deal">
                                                            @csrf

                                                            <input type="hidden" name="id"
                                                                value="{{ $matchedDealId }}">

                                                            <label class="switcher mx-auto">
                                                                <input type="checkbox"
                                                                    class="switcher_input toggle-switch-message"
                                                                    id="deal-of-the-day{{ $matchedDealId }}"
                                                                    name="status" value="1"
                                                                    {{ $matchedDealStatus == 1 ? 'checked' : '' }}
                                                                    data-toggle-id="deal-of-the-day{{ $matchedDealId }}"
                                                                    data-modal-id="toggle-status-modal"
                                                                    data-on-image="deal-of-the-day-status-on.png"
                                                                    data-off-image="deal-of-the-day-status-off.png"
                                                                    data-on-title="{{ translate('want_to_Turn_ON_Deal_of_the_Day_Status') . '?' }}"
                                                                    data-off-title="{{ translate('want_to_Turn_OFF_Deal_of_the_Day_Status') . '?' }}"
                                                                    data-on-message="<p>{{ translate('if_enabled_this_deal_of_the_day_will_be_available_on_the_website_and_customer_app') }}</p>"
                                                                    data-off-message="<p>{{ translate('if_disabled_this_deal_of_the_day_will_be_hidden_from_the_website_and_customer_app') }}">
                                                                </p>">
                                                                <span class="switcher_control"></span>
                                                            </label>
                                                        </form>
                                                    @else
                                                        {{-- No deal → open deal modal --}}
                                                        <label class="switcher mx-auto">
                                                            <input type="checkbox"
                                                                class="switcher_input toggle-switch-message"
                                                                name="status" value="1" data-modal-id="popup-modal"
                                                                onclick="openDealModal({{ $product['id'] }})">
                                                            </p>">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    @endif
                                                </td>

                                                <td>
                                                    <div class="d-flex justify-content-center gap-10">
                                                        <a class="btn btn-outline--primary btn-sm square-btn"
                                                            href="{{ route('admin.products.update', [$product['id']]) }}">
                                                            <i class="tio-edit"></i>
                                                        </a>
                                                        <a class="btn btn-outline-danger btn-sm square-btn delete-data"
                                                            href="javascript:" data-id="product-{{ $product['id'] }}">
                                                            <i class="tio-delete"></i>
                                                        </a>
                                                    </div>
                                                    <form action="{{ route('admin.products.delete', [$product['id']]) }}"
                                                        method="post" id="product-{{ $product['id'] }}">
                                                        @csrf @method('delete')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="table-responsive mt-4">
                                <div class="px-4 d-flex justify-content-lg-end">
                                    {{ $products->links() }}
                                </div>
                            </div>
                            @if (count($products) == 0)
                                <div class="text-center p-4">
                                    <img class="mb-3 w-160"
                                        src="{{ asset('public/assets/back-end/svg/illustrations/sorry.svg') }}"
                                        alt="{{ translate('image_description') }}">
                                    <p class="mb-0">{{ translate('no_data_to_show') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="popup-modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{ route('admin.deal.day') }}"
                                class="text-start onsubmit-disable-action-button" method="post">
                                @csrf
                                @php($language = getWebConfig(name: 'pnc_language'))
                                @php($defaultLanguage = 'en')
                                @php($defaultLanguage = $language[0])

                                <input type="hidden" name="product_id" id="deal-product-id">
                                <ul class="nav nav-tabs w-fit-content mb-4">
                                    @foreach ($language as $lang)
                                        <li class="nav-item text-capitalize">
                                            <a class="nav-link lang-link {{ $lang == $defaultLanguage ? 'active' : '' }}"
                                                href="javascript:"
                                                id="{{ $lang }}-link">{{ getLanguageName($lang) . '(' . strtoupper($lang) . ')' }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="form-group">
                                    @foreach ($language as $lang)
                                        <div class="row {{ $lang != $defaultLanguage ? 'd-none' : '' }} lang-form"
                                            id="{{ $lang }}-form">
                                            <div class="col-md-12">
                                                <label for="name">{{ translate('title') }}
                                                    ({{ strtoupper($lang) }})
                                                </label>
                                                <input type="text" name="title[]" class="form-control" id="title"
                                                    placeholder="{{ translate('ex') . ' ' . ':' . ' ' . translate('LUX') }}"
                                                    {{ $lang == $defaultLanguage ? 'required' : '' }}>
                                            </div>
                                        </div>
                                        <input type="hidden" name="lang[]" value="{{ $lang }}"
                                            id="lang">
                                    @endforeach
                                </div>
                                <div class="d-flex justify-content-end gap-3">
                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                        class="btn btn-secondary px-5 reset-button">{{ translate('cancel') }}</button>
                                    <button type="submit"
                                        class="btn btn--primary px-5">{{ translate('submit') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('public/assets/back-end/js/admin/deal.js') }}"></script>
    <script>
        function openDealModal(productId) {
            // Set hidden input value
            document.getElementById('deal-product-id').value = productId;
        }
    </script>
@endpush
