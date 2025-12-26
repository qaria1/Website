@extends('layouts.back-end.app-seller')

@push('css_or_js')
    <style>
        :root {
            --primary-clr: #0093fa;
            --secondary-clr: #99a7ba;
            --title-clr: #334257;
            --base-clr: #f8923b;
            --border-clr: #99a7ba45;
            --warning-clr: #ff7500;
            --danger-clr: #ff6d6d;
            --success-clr: #00aa6d;
            --info-clr: #0096ff;
        }

        .btn--warning {
            background: var(--warning-clr) !important;
            border-color: var(--warning-clr) !important;
        }

        .__billing-subscription .main-title {
            font-size: 18px;
            margin-bottom: 22px;
        }

        @media screen and (min-width: 1200px) and (max-width: 1650px) {
            .__billing-subscription .check-item {
                max-width: 248px;
                padding-bottom: 15px;
            }
        }

        .bg-FCFCFC {
            background: #fcfcfc;
            border-radius: 8px;
        }

        .__billing-item {
            display: flex;
            align-items: center;
            width: 33.3333333333%;
            padding: 15px 25px;
        }

        .__billing-item img {
            width: 40px;
        }

        .__billing-item .info {
            font-size: 12px;
        }

        .__billing-item .subtitle {
            font-weight: 600;
            font-size: 26px;
            line-height: 1;
            margin-top: 13px;
        }

        .__billing-item:not(:last-child)::after {
            content: "";
            width: 1px;
            height: 100%;
            max-height: 50px;
            background: rgba(0, 0, 0, 0.45);
            display: block;
            margin-inline-start: auto;
        }

        @media (max-width: 991px) {
            .__billing-item {
                width: 50%;
            }

            .__billing-item::after {
                display: none !important;
            }
        }

        @media (max-width: 767px) {
            .__billing-item {
                width: 100%;
            }
        }

        @media (max-width: 374px) {
            .__billing-item img {
                width: 35px;
            }

            .__billing-item .subtitle {
                font-size: 20px;
            }
        }

        .__plan-details {
            border-radius: 8px;
            padding: 20px 28px;
        }

        .__plan-details-top .left {
            padding-inline-end: 20px;
        }

        .__plan-details-top .left .name {
            color: #006ab4;
            font-size: 26px;
            margin-bottom: 0;
        }

        .__plan-details-top .right {
            font-size: 30px;
            display: flex;
            align-items: flex-end;
            margin-top: 15px;
        }

        .__plan-details-top .right small {
            font-size: 65%;
        }

        @media (max-width: 374px) {
            .__plan-details-top .left {
                padding-inline-end: 0;
            }

            .__plan-details-top .right {
                font-size: 26px;
            }
        }

        .rounded-full {
            border-radius: 50%;
        }

        @media (max-width: 575px) {
            .__btn-container button {
                width: 100%;
            }
        }

        @media (min-width: 1200px) {
            .__modal .modal-xl {
                max-width: 1013px;
            }
        }

        .__modal .close {
            border: 1px solid #000000;
            color: #000000;
            opacity: 1;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            padding: 0;
            font-size: 14px;
        }

        .__modal .modal-content {
            border-radius: 20px;
        }

        @media (min-width: 1440px) {
            .__modal .modal-content {
                padding-bottom: 50px;
                transform: translateX(125px);
            }
        }

        .__modal .modal-title {
            font-size: 22px;
        }

        @media (max-width: 475px) {
            .__modal .modal-title {
                font-size: 18px;
            }
        }

        .__plan {
            background: #ffffff;
            box-shadow: 0px 0px 25px rgba(33, 20, 0, 0.1);
            border-radius: 20.175px;
            padding: 34px 38px;
            position: relative;
            border: 1px solid transparent;
        }

        .__plan .plan-header .title {
            font-weight: 700;
            font-size: 21.28px;
            line-height: 150%;
            color: #0093fa;
            margin-bottom: 1px;
            display: flex;
        }

        .__plan .plan-header .duration {
            font-size: 11px;
            line-height: 13px;
            display: flex;
            align-items: center;
            color: #1e1c1a;
        }

        .__plan .plan-header .duration strong {
            font-weight: 600;
            margin-inline-end: 4px;
        }

        .__plan .plan-header .price {
            font-weight: 700;
            font-size: 35px;
            line-height: 42px;
            color: #1e1c1a;
            text-align: center;
            margin-top: 16px;
            margin-bottom: 22px;
        }

        .__plan .plan-header .check-plan-icon {
            display: none;
            width: 20px !important;
            margin-inline-start: 12px;
        }

        .__plan .plan-info {
            font-weight: 500;
            font-size: 12px;
            line-height: 15px;
            color: #1e1c1a;
            padding: 0;
            margin: 0;
            margin-bottom: 31px;
        }

        .__plan .plan-info li {
            list-style: none;
            display: flex;
        }

        .__plan .plan-info li:not(:last-child) {
            margin-bottom: 17px;
        }

        .__plan .plan-info li img {
            width: 18px;
            height: 18px;
            margin-inline-end: 10px;
        }

        .__plan .plan-info li span {
            width: 0;
            flex-grow: 1;
        }

        .__plan .btn {
            background: #0079e3;
            border-radius: 14.03px;
            height: 49.28px;
            padding: 0 24px;
            text-transform: none;
        }

        .__plan .plan-selector {
            position: absolute;
            inset: 0;
        }

        @media (max-width: 991px) {
            .__plan {
                padding: 24px;
            }
        }

        .__plan-item .__plan-btns {
            display: inline-block;
        }

        .__plan-item.active .__plan {
            border-color: #006bc9;
        }

        .__plan-item.active .check-plan-icon {
            display: block;
        }

        .__plan-btns {
            position: relative;
        }

        .__plan-btns label {
            position: absolute;
            inset: 0;
            z-index: 1;
            cursor: pointer;
        }

        .plan-slider {
            max-width: 815px;
            margin: 0 auto;
        }

        .plan-slider .owl-stage-outer {
            overflow: visible !important;
        }

        .w-20px {
            width: 20px !important;
        }

        .card .btn--container .btn:not(.action-btn) {
            min-width: 120px;
        }

        p.start {
            /* font-family: 'Open Sans', sans-serif;
                                font-size: 10px;
                                line-height: 28px; */
            text-align: justify;
            display: inline;
        }

        h1.start {
            /* font-family: 'Open Sans', sans-serif;
                                font-size: 16px;
                                line-height: 28px; */
            margin: 0;
            display: inline-block;
        }

        .plan-slider {
            max-width: 815px;
            margin: 0 auto;
        }

        .plan-slider .owl-stage-outer {
            overflow: visible !important;
        }
    </style>
@endpush
@section('title', translate('subscription_history'))

@section('content')
    {{-- <div class="content container-fluid">

        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h1 text-capitalize">
                <img src="{{ asset('public/assets/back-end/img/inhouse-product-list.png') }}" alt="">
                {{ translate('subscription_History_List') }}
            </h2>

            @if ($seller->getActivePlanForSeller($seller))
                <div class="d-flex align-items-center">
                    <div class="alert alert-light border mr-2" role="alert">
                        <span class="mr-3">{{ translate('product_left_for_current_plan') }}: <span class="badge-primary badge-pill">
                            {{ ($seller->getDefaultProductCountForPlan($seller) - $seller->getTotalProductForActivePlan($seller)) ?? '-' }}
                        </span></span>
                    </div>
                    <div class="alert alert-light border" role="alert">
                        <span class="mr-3">{{ translate('current_active_plan') }}: <span class="badge-primary badge-pill">
                            {{ $seller->getActivePlanForSeller($seller)?->plan?->name ?? translate('none') }} {{ $seller->getActivePlanForSeller($seller)?->is_trial ? '('.translate('trial').')' : '' }}
                        </span></span>
                    </div>
                </div>
            @else
                <div class="d-flex align-items-center">
                    <div class="alert alert-danger border mr-2" role="alert">
                        <span>{{ translate('no_active_subscription_found') }}</span>
                    </div>
                </div>
            @endif
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ url()->current() }}" method="GET">
                    <input type="hidden" value="{{ request('status') }}" name="status">
                    <div class="row gx-2">
                        <div class="col-12">
                            <h4 class="mb-3">{{ translate('filter') }}</h4>
                        </div>

                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label for="name" class="title-color">{{ translate('subscription_plan') }}</label>
                                <select class="js-select2-custom form-control action-get-billing-types"
                                    name="subscription_plan_id" id="subscription-plan-select"
                                    data-url-prefix="{{ route('vendor.subscriptions.get-billing-types') . '?plan_id=' }}"
                                    data-element-id="billing-type-select" data-element-type="select">
                                    <option
                                        value="{{ request('subscription_plan_id') != null ? request('subscription_plan_id') : null }}"
                                        {{ old('subscription_plan_id') == '' ? 'selected' : '' }} disabled>
                                        {{ translate('select_plan') }}
                                    </option>

                                    @forelse ($subscriptionPlans as $plan)
                                        <option value="{{ $plan->id }}"
                                            {{ old('subscription_plan_id') == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->name }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label for="name" class="title-color">{{ translate('billing_type') }}</label>
                                <select class="js-select2-custom form-control" name="billing_id" id="billing-type-select"
                                    data-element-type="select">
                                    <option value="{{ request('billing_id') != null ? request('billing_id') : null }}"
                                        selected {{ request('billing_id') != null ? '' : 'disabled' }}>
                                        {{ translate('select_subscription_first') }}
                                    </option>

                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex gap-3 justify-content-end">
                                <a href="{{ route('vendor.business-settings.subscription.index') }}"
                                    class="btn btn-secondary px-5">
                                    {{ translate('reset') }}
                                </a>
                                <button type="submit" class="btn btn--primary px-5 action-get-element-type">
                                    {{ translate('show_data') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mt-20">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row justify-content-end">
                            <div class="col-lg-8 mt-3 mt-lg-0 d-flex flex-wrap gap-3 justify-content-lg-end">
                                <div>
                                    <button type="button" class="btn btn-outline--primary" data-toggle="dropdown">
                                        <i class="tio-download-to"></i>
                                        {{ translate('export') }}
                                        <i class="tio-chevron-down"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('vendor.products.export-excel', ['brand_id' => request('brand_id'), 'category_id' => request('category_id'), 'sub_category_id' => request('sub_category_id'), 'sub_sub_category_id' => request('sub_sub_category_id'), 'searchValue' => request('searchValue')]) }}">
                                                <img width="14"
                                                    src="{{ asset('public/assets/back-end/img/excel.png') }}"
                                                    alt="">
                                                {{ translate('excel') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable"
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 text-start">
                            <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{ translate('SL') }}</th>
                                    <th>{{ translate('subscription_plan') }}</th>
                                    <th class="text-center">{{ translate('status') }}</th>
                                    <th class="text-center">{{ translate('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subscriptionHistory as $key => $plan)
                                    <tr>
                                        <th scope="row">{{ $key + 1 }}</th>
                                        <td>
                                            {{ $plan->plan?->name ?? '-' }}
                                        </td>
                                        <td class="text-center">
                                            @if ($plan->plan->subscriptions->where('seller_id', $plan->seller_id)->where('plan_id', $plan->plan_id)->where('status', true)->count() > 0)
                                                <label class="badge badge-soft-primary">{{ translate('active') }}</label>
                                            @else
                                                <label class="badge badge-soft-danger">{{ translate('inactive') }}</label>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a class="btn btn-outline-info btn-sm square-btn"
                                                    title="{{ translate('view') }}"
                                                    href="#subscriptionDetailModal{{ $plan->id }}" data-toggle="modal" data-target="#subscriptionDetailModal{{ $plan->id }}">
                                                    <i class="tio-invisible"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if (count($subscriptionHistory) == 0)
                        <div class="text-center p-4">
                            <img class="mb-3 w-160" src="{{ asset('public/assets/back-end/svg/illustrations/sorry.svg') }}"
                                alt="{{ translate('image_description') }}">
                            <p class="mb-0">{{ translate('no_data_to_show') }}</p>
                        </div>
                    @endif
                </div>
            </div>
    </div> --}}

    <div class="content container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mb-3">
                <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                    <img src="{{ asset('/public/assets/back-end/img/add-new-seller.png') }}" alt="">
                    {{ translate('my_subscription') }}
                </h2>
            </div>
            @if ($seller_subscription?->status == 1)
                <div class="d-flex align-items-center">
                    <div class="alert alert-light border mr-2" role="alert">
                        <span class="mr-3">{{ translate('product_left_for_current_plan') }}: <span class="badge-primary badge-pill">
                            {{ isset($seller_subscription) ? ((int)$seller_subscription?->max_product_upload - $seller->getTotalProductForActivePlan($seller)) : '-' }}
                        </span></span>
                    </div>
                    <div class="alert alert-light border" role="alert">
                        <span class="mr-3">{{ translate('current_active_plan') }}: <span class="badge-primary badge-pill">
                            {{ $seller->getActivePlanForSeller($seller)?->plan?->name ?? translate('none') }} {{ $seller->getActivePlanForSeller($seller)?->is_trial ? '('.translate('trial').')' : '' }}
                        </span></span>
                    </div>
                </div>
            @else
                <div class="alert alert-light border" role="alert">
                    <span class="mr-3">{{ translate('last_used_plan') }}: <span class="badge-primary badge-pill">
                        {{ $seller_subscription?->plan?->name ?? translate('none') }} {{ $seller_subscription?->is_trial ? '('.translate('trial').')' : '' }}
                    </span></span>
                </div>
            @endif
        </div>

        <div class="page-header">
            {{-- <div class="flex-between row mx-1">
                <div>
                    <h1 class="page-header-title">
                        {{ $seller?->shop->name ?? translate('shop_Name') . ' : ' . translate('update_Please') }}</h1>
                </div>
                <div class="btn--container justify-content-end">
                    @if (empty($seller_subscription))
                        <button class="btn btn--primary" data-toggle="modal" data-target="#subscription-modal">
                        <span class="ml-1">{{ translate('Add_Subscription_Package') }}</span> </button>
                    @endif
                    @if (isset($seller_subscription) && $seller_subscription->current_end <= Carbon\Carbon::today()->addDays('11'))
                        <button class="btn btn--warning my-2" data-toggle="modal" data-target="#subscription-modal">
                            <span class="ml-1">{{ translate('renew_now') }}</span> </button>
                    @endif
                </div>
            </div> --}}

            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <ul class="nav nav-tabs flex-wrap page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active"
                            href="{{ route('vendor.business-settings.subscription.index')}}">{{ translate('subscription_detail') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ route('vendor.business-settings.subscription.transaction') }}">{{ translate('transactions') }}</a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- <div class="row mb-4">
            @php($currentEndDate = \App\Models\SellerSubscription::where('seller_id', $seller->id)->where('status', true)->first()?->current_end)

            @if ($currentEndDate)
                @php($currentPlanEndDate = \Carbon\Carbon::createFromFormat('Y-m-d', $currentEndDate))
                @php($currentDate = \Carbon\Carbon::now())

                @php($daysLeft = $currentPlanEndDate->diffInDays($currentDate))

                @if ($currentDate < $currentPlanEndDate && $daysLeft < 11)
                    <div class="col-md-12">
                        <span class="alert alert-danger mt-3 mr-2" role="alert">
                            <i class="tio-warning"></i>
                            {{ translate('you_have') . ' ' . $daysLeft . ' ' . translate('_days_left_for_the_current_plan_to_expire') }}
                        </span>
                    </div>
                @endif
            @endif
        </div> --}}

        @if (isset($seller_subscription))
            <div class="card __billing-subscription mb-4">
                <div class="card-body">
                    <h4 class="main-title">{{ translate('Billing') }}</h4>
                    <div class="bg-FCFCFC d-flex flex-wrap">
                        <div class="__billing-item">
                            <img src="{{ asset('/public/assets/back-end/img/subscription/1.png') }}"
                                alt="back-end/img/subscription">
                            <div class="flex-grow pl-3 pl-sm-4">
                                <div class="info">{{ translate('expire_date') }}</div>
                                @if ($seller_subscription->status == 0)
                                    <h4 class="subtitle" style="color: red"> {{ translate('Plan_Expired') }}</h4>
                                    <span>{{ Carbon\Carbon::parse($seller_subscription->current_end)->format('F j, Y') }}</span>
                                @else
                                    <h4 class="subtitle">
                                        {{ Carbon\Carbon::parse($seller_subscription->current_end)->format('F j, Y') }}
                                    </h4>
                                @endif
                            </div>
                        </div>
                        <div class="__billing-item">
                            <img src="{{ asset('/public/assets/back-end/img/subscription/2.png') }}"
                                alt="back-end/img/subscription">
                            <div class="flex-grow pl-3 pl-sm-4">
                                <div class="info">{{ translate('Total_bill') }}</div>
                                <h4 class="subtitle">
                                    {{ isset($total_bill) ? setCurrencySymbol(amount: usdToDefaultCurrency(amount: $total_bill), currencyCode: getCurrencyCode()) : '' }}
                                </h4>
                                <span>
                                    {{ $seller_subscription->is_trial ? '(' . translate('unpayable') . ')' : '' }}
                                </span>
                            </div>
                        </div>
                        <div class="__billing-item">
                            <img src="{{ asset('/public/assets/back-end/img/subscription/3.png') }}"
                                alt="back-end/img/subscription">
                            <div class="flex-grow pl-3 pl-sm-4">
                                <div class="info">{{ translate('Number_of_Uses') }}</div>
                                <h4 class="subtitle">{{ $seller_subscription->total_package_renewed + 1 ?? '' }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-2">
                        <h4 class="card-title mb-4">
                            <span class="card-header-icon">
                                <img class="w-20px" src="{{ asset('/public/assets/back-end/img/subscription-plan.png') }}"
                                    alt="">
                            </span>
                            <span>{{ translate('Subscription_Plan') }}</span>
                        </h4>
                        <div class="bg-FCFCFC __plan-details">
                            <div class="d-flex flex-wrap flex-md-nowrap justify-content-between __plan-details-top">
                                <div class="left">
                                    <h3 class="name">{{ $seller_subscription?->plan?->name ?? '' }} {{ $seller_subscription->is_trial ? '(' . translate('trial') . ')' : '' }}</h3>
                                    {{-- <div class="font-medium text--title">{{ 'plan description here' }}</div> --}}
                                </div>
                                <h3 class="right">
                                    {{ isset($seller_subscription->price) ? setCurrencySymbol(amount: usdToDefaultCurrency(amount: $seller_subscription->price), currencyCode: getCurrencyCode()) : '' }}
                                    /
                                    <small class="font-medium text--title">{{ $seller_subscription->validity ?? '' }}
                                        {{ translate('Days') }}</small>
                                </h3>
                            </div>

                            {{-- <div class="check--item-wrapper mx-0 mb-0">
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" checked
                                            class="form-check-input">
                                        <label class="form-check-label qcont text-dark" for="account">
                                            {{ $seller_subscription->max_product_lifecycle }} {{ translate('days_product_lifecycle') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" {{ $seller_subscription->discount == 1 ? 'checked' : '' }}
                                            class="form-check-input">
                                        <label class="form-check-label qcont text-dark" for="account">{{ translate('discount') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" class="form-check-input"
                                            {{ $seller_subscription->product_top_search == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label qcont text-dark"
                                            for="account">{{ translate('product_top_search') }}</label>
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox"class="form-check-input"
                                            {{ $seller_subscription->item_verification == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label  text-dark"
                                            for="account">{{ translate('item_verification') }}</label>
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" class="form-check-input" checked>
                                        <label class="form-check-label  text-dark" for="account">
                                            @if ($seller_subscription->max_product_upload == 'unlimited')
                                                {{ translate('unlimited_product_Upload') }}
                                            @else
                                                {{ $seller_subscription->max_product_upload }} {{ translate('product_Upload') }}
                                        </label>
                                        <small style="">
                                            ( {{ 'count the days left value here' }} {{ translate('left') }})
                                        </small>
                                        @endif
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" class="form-check-input"
                                            {{ $seller_subscription->product_photoshoot == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label text-dark"
                                            for="account">{{ translate('product_photoshoot') }}</label>
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" class="form-check-input"
                                            {{ $seller_subscription->free_delivery == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label  text-dark"
                                            for="account">{{ translate('free_delivery') }}</label>
                                    </div>
                                </div>
                            </div> --}}

                        </div>

                        {{-- <div class="__btn-container btn--container justify-content-end" style="display: flex;">
                            @if ($seller_subscription->status == 1)
                                <button class="btn btn-outline-danger h--45px form-alert mr-3" href="javascript:"
                                    data-id="subscription-{{ $seller_subscription->id }}" data-message="{{ translate('You_want_to_Cancel_the_Plan_for') }} {{ $seller_subscription?->plan?->name }} {{ $seller_subscription->is_trial ? '(' . translate('trial') . ')' : '' }}">
                                    <span class="ml-1">{{ translate('Cancel_Subscription') }}</span>
                                </button>
                                <form action="{{ route('admin.sellers.subscription.cancel') }}" method="post"
                                    id="subscription-{{ $seller_subscription->id }}">
                                    @csrf
                                    <input type="hidden" name="subscription_plan" value="{{ $seller_subscription?->plan?->id }}">
                                    <input type="hidden" name="seller" value="{{ $seller->id }}">
                                </form>
                            @endif
                            <button class="btn btn--primary" data-toggle="modal" data-target="#subscription-modal">
                                <span class="ml-1">{{ translate('Change_/_Renew_Subscription_Plan') }}</span>
                            </button>
                        </div> --}}
                    </div>
                </div>
            </div>

        @else
            <div class="d-flex justify-content-center">
                <div class="text-center">
                    <img src="{{ asset('public/assets/back-end/svg/illustrations/sorry.svg') }}" alt="public" class="mb-3 w-160 mx-auto">
                    <h4 class="">
                        {{ translate('No_Subscription_Plan_Available') . '!' }}
                    </h4>
                </div>
            </div>
        @endif
    </div>

    <span id="message-select-word" data-text="{{ translate('select') }}"></span>

    {{-- @foreach ($subscriptionHistory as $key => $plan)
        @include('layouts.front-end.partials.modal._vendor-subscription-detail',['plan' => $plan,])
    @endforeach --}}

@endsection

@push('script')
    <script>
        $('.action-get-billing-types').on('change', function() {
            let getUrlPrefix = $(this).data('url-prefix');
            let id = $(this).data('element-id');
            let getElementType = $(this).data('element-type');
            let value = $(this).val();

            // Ajax here to get billing types of the selected subscription plan
            getPlanBillingTypes(getUrlPrefix, id, getElementType, value)

        });

        function getPlanBillingTypes(getUrlPrefix, id, getElementType, value) {
            console.log(getUrlPrefix + value)
            let message = $('#message-select-word').data('text');
            $('#billing-type-select').empty().append(`<option value="" selected disabled>---` + message + `---</option>`);
            $.get({
                url: getUrlPrefix + value,
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    if (getElementType === 'select') {
                        $('#' + id).empty().append(data.select_tag);
                    }
                },
            });
        }
    </script>
@endpush
