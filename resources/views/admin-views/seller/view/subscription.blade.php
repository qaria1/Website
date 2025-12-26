@extends('layouts.back-end.app')
@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/back-end/css/admin/croppie.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/public/assets/front-end/css/owl.carousel.css')}}">

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


        /* Modal styles if it works here */
        .payment__method {
    display: block;
}

.payment__method-card {
    cursor: pointer;
    position: relative;
    display: block;
    padding: 25px 35px 55px;
    background: #ffffff;
    box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.05);
    border-radius: 10px;
    border: 1px solid transparent;
    transition: all ease 0.3s;
}

.payment__method-card .title {
    margin-bottom: 17px;
}

.payment__method-card p {
    font-size: 14px;
    color: #242a30;
    line-height: 20px;
}

.payment__method-card .title {
    font-size: 18px;
    text-transform: capitalize;
    transition: all ease 0.3s;
}

.payment__method-card .checkicon {
    width: 33px;
    height: 33px;
    background: url("./check.png") #ef7822 no-repeat center center/20px 20px;
    display: block;
    position: absolute;
    inset-inline-end: 20px;
    top: 15px;
    border-radius: 50%;
    transition: all ease 0.3s;
    transform: scale(0);
    box-shadow: 2px 2px rgba(239, 120, 34, 0.3411764706);
}

@media (max-width: 575px) {
    .payment__method-card {
        padding: 25px 18px;
    }
    .payment__method-card .title {
        margin-bottom: 12px;
    }
    .payment__method-card .checkicon {
        inset-inline-end: 15px;
        top: 15px;
        width: 25px;
        height: 25px;
    }
}
.payment__method input:checked ~ .payment__method-card {
    background: linear-gradient(97.37deg, rgba(255, 226, 202, 0.3) 0.19%, rgba(255, 249, 243, 0.3) 51.56%, rgba(255, 229, 207, 0.3) 100%);
    border: 1px solid #f8923b;
    border-radius: 10px;
}

.payment__method input:checked ~ .payment__method-card .checkicon {
    transform: scale(1);
}

.payment__method input:checked ~ .payment__method-card .title {
    color: #f8923b;
}

.subscription__plan-info {
    font-size: 14px;
    font-weight: 500;
    color: #334257;
}

.subscription__plan-info .subtitle {
    margin: 0;
    margin-top: 5px;
    font-size: 22px;
    font-weight: 600;
}

.subscription__plan-info .subtitle sub {
    bottom: 0;
    font-size: 12px;
}

.bg-ECEEF1 {
    background: #eceef1;
}

.rounded-20 {
    border-radius: 20px;
}

.subscription__plan-info-wrapper {
    padding: 20px;
}

@media (min-width: 768px) and (max-width: 991px) {
    .subscription__plan-info-wrapper .subscription__plan-info .subtitle {
        font-size: 18px;
    }
}
@media (max-width: 991px) {
    .subscription__plan-info-wrapper {
        padding-top: 35px;
        padding-bottom: 35px;
    }
    .subscription__plan-info-wrapper .subscription__plan-info {
        max-width: 350px;
        margin: 0 auto;
    }
}
@media (max-width: 991px) and (max-width: 425px) {
    .subscription__plan-info-wrapper .subscription__plan-info .subtitle {
        font-size: 18px;
    }
}
.change-plan-wrapper {
    max-width: 694px;
    margin: 0 auto 30px;
    display: flex;
    flex-wrap: wrap;
}

@media (min-width: 92px) {
    .change-plan-wrapper {
        margin-bottom: 55px;
    }
}
.change-plan-wrapper .plan-item {
    width: 100%;
    max-width: 265px;
    border-radius: 8px;
    position: relative;
    background: #fffefe;
    box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.05);
    padding-bottom: 28px;
}

.change-plan-wrapper .plan-item .plan-header {
    position: relative;
    padding: 3px;
    margin-bottom: 10px;
}

.change-plan-wrapper .plan-item .plan-header .plan-header-shape {
    width: 100%;
}

.change-plan-wrapper .plan-item .plan-header svg {
    width: 100%;
    min-height: 159px;
    -o-object-fit: cover;
    object-fit: cover;
    -o-object-position: bottom center;
    object-position: bottom center;
}

.change-plan-wrapper .plan-item .plan-header .title {
    position: absolute;
    inset-inline-start: 50%;
    top: 42%;
    transform: translate(-50%, -50%);
    color: #fff;
    font-size: 22px;
    line-height: 1;
    z-index: 999;
    opacity: 1;
    max-width: calc(100% - 10px);
    text-align: center;
}

.change-plan-wrapper .plan-item .price {
    background: linear-gradient(90deg, #f24c88 -160.18%, #f25285 -68.33%, #f3647d 56.58%, #f58071 199.88%, #f5886d 234.71%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: rgba(0, 0, 0, 0);
    font-size: 36px;
    line-height: 1;
    font-weight: 700;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: flex-end;
}

.change-plan-wrapper .plan-item .price sub {
    font-size: 11px;
    bottom: 0;
    background: #1e1c1a;
    -webkit-background-clip: text;
    display: inline-block;
    -webkit-text-fill-color: #000000;
    line-height: 1;
    font-weight: 400;
    font-family: var(--body);
}

.change-plan-wrapper .plan-item:nth-child(3) .price {
    background: linear-gradient(90deg, #feca7a -122.08%, #fec47a -37.19%, #fdb278 78.28%, #fc9676 210.74%, #fc9076 235.07%);
    -webkit-background-clip: text;
}

.change-plan-wrapper .plan-item:nth-child(3) .price sub {
    background: #1e1c1a;
    -webkit-background-clip: text;
    display: inline-block;
    -webkit-text-fill-color: #000000;
}

.change-plan-wrapper .plan-item .checkicon {
    width: 41px;
    height: 41px;
    background: url("../img/check.png") #f8923b no-repeat center center/30px 30px;
    display: block;
    position: absolute;
    inset-inline-end: -12px;
    top: -12px;
    border-radius: 50%;
    transition: all ease 0.3s;
    box-shadow: 2px 2px rgba(242, 76, 136, 0.3411764706);
}

@media (max-width: 767px) {
    .change-plan-wrapper .plan-item .checkicon {
        width: 30px;
        height: 30px;
        background-size: 20px 20px;
        inset-inline-end: -10px;
        top: -10px;
    }
}
@media (max-width: 375px) {
    .change-plan-wrapper .plan-item .checkicon {
        inset-inline-end: 0px;
        top: 0px;
    }
}
@media (max-width: 991px) {
    .change-plan-wrapper {
        justify-content: center;
    }
    .change-plan-wrapper .plan-seperator-arrow {
        width: 100%;
        display: block;
        text-align: center;
        margin: 20px 0;
    }
    .change-plan-wrapper .plan-seperator-arrow img {
        max-width: 40px;
        transform: rotate(90deg);
    }
}
/* Modal style if it works end */
    </style>
@endpush

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
                <div class="btn--container justify-content-end">
                    {{-- @if (empty($seller_subscription))
                        <button class="btn btn--primary" data-toggle="modal" data-target="#subscription-modal">
                        <span class="ml-1">{{ translate('Add_Subscription_Package') }}</span> </button>
                    @endif --}}
                    @if (isset($seller_subscription) && $seller_subscription->current_end <= Carbon\Carbon::today()->addDays('11'))
                        <button class="btn btn--warning my-2" data-toggle="modal" data-target="#subscription-modal">
                            <span class="ml-1 text-white">{{ translate('renew_now') }}</span> </button>
                    @endif
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
                        <a class="nav-link"
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
                        <a class="nav-link active"
                            href="{{ route('admin.sellers.view', ['id' => $seller->id, 'tab' => 'subscription']) }}">{{ translate('subscription') }}</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="row mb-4">
            {{-- get the active plan current end date value --}}
            @php($currentEndDate = \App\Models\SellerSubscription::where('seller_id', $seller->id)->where('status', true)->first()?->current_end)

            @if ($currentEndDate)
                {{-- Convert the dates to Carbon objects --}}
                @php($currentPlanEndDate = \Carbon\Carbon::createFromFormat('Y-m-d', $currentEndDate))
                @php($currentDate = \Carbon\Carbon::now())

                {{-- Calculate the difference in days --}}
                @php($daysLeft = $currentPlanEndDate->diffInDays($currentDate))

                @if ($currentDate < $currentPlanEndDate && $daysLeft < 11)
                    <div class="col-md-12">
                        <span class="alert alert-danger mt-3 mr-2" role="alert">
                            <i class="tio-warning"></i>
                            {{ translate('seller_has') . ' ' . $daysLeft . ' ' . translate('_days_left_for_the_current_plan_to_expire') }}
                        </span>
                    </div>
                @endif
            @endif
        </div>

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

                        <div class="__btn-container btn--container justify-content-end" style="display: flex;">
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
                        </div>
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

    <div class="row pb-4 d--none text-start" id="subscription-card">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 text-capitalize">{{ translate('subscription_plan_form') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.sellers.subscription.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="seller_id" value="{{ $seller->id }}">
                        <div class="row g-3">
                            <div class="form-group">
                                <label for="isTrialPlan">{{ translate('trial_plan') }}? ({{ translate('default') }}:
                                    {{ isset($defaultTrialPlan) ? $defaultTrialPlan->plan?->name : '' }})</label>
                                <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                    data-placement="right"
                                    title="{{ translate('you_can_set_which_plan_should_be_the_default_in_business_settings') }}">
                                    <img width="16" src="{{ asset('/public/assets/back-end/img/info-circle.svg') }}"
                                        alt="">
                                </span>
                                <br>
                                <label class="switcher">
                                    <input type="checkbox" name="is_trial_plan" class="switcher_input" id="isTrialPlan"
                                        @checked(old('is_trial_plan'))>
                                    <span class="switcher_control"></span>
                                </label>
                            </div>
                        </div>

                        <div class="plan-selection-select row">
                            <div class="col-lg-6 form-group">
                                <label for="subscription">{{ translate('select_subscription') }}</label>
                                <select name="subscription_plan"
                                    class="form-control fs-13 border-aliceblue max-240px action-get-billing-types"
                                    id="subscription-select"
                                    data-url-prefix="{{ route('admin.subscriptions.get-billing-types') . '?plan_id=' }}"
                                    data-element-id="billing-type-select" data-element-type="select">
                                    <option value="" disabled selected>
                                        {{ translate('choose_subscription_plan') }}</option>
                                    @foreach ($subscriptionPlans as $plan)
                                        <option value="{{ $plan->id }}">
                                            {{ $plan->name }} -
                                            ({{ App\Models\SellerSubscription::where('plan_id', $plan->id)->where('status', true)->count() }}
                                            {{ translate('total_vendor_subscribed') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 form-group">
                                <label for="billing_type">{{ translate('select_billing_type') }}</label>
                                <select name="billing_type" class="form-control max-240px action-set-billing-type"
                                    id="billing-type-select">
                                    <option value="" disabled selected>{{ translate('choose_billing_type') }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        @if ($seller->brand_id === DEFAULT_BRAND)
                            <div class="form-group">
                                <label for="isVendorExclusiveBrand">{{ translate('is_vendor_exclusive_brand') }}?
                                    {{-- ({{ translate('default') }}: {{ isset($defaultTrialPlan) ? $defaultTrialPlan->plan?->name : '' }}) --}}
                                </label>
                                <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                    data-placement="right"
                                    title="{{ translate('you_must_register_the_brand_under_the_exclusive_brands_menu_if_you_do_not_get_the_list') }}">
                                    <img width="16" src="{{ asset('/public/assets/back-end/img/info-circle.svg') }}"
                                        alt="">
                                </span>
                                <br>
                                <label class="switcher">
                                    <input type="checkbox" name="is_vendor_exclusive_brand" class="switcher_input"
                                        id="isVendorExclusiveBrand" @checked(old('is_vendor_exclusive_brand'))>
                                    <span class="switcher_control"></span>
                                </label>
                            </div>
                        @endif

                        <div class="seller-brand-selection row d-none">
                            <div class="col-lg-6 form-group">
                                <div class="form-group">
                                    <label for="brand-selector"
                                        class="title-color">{{ translate('select_brand') }}</label>
                                    <select id="brand-selector" class="js-select2-custom form-control" name="brand_id">
                                        <option {{ old('brand_id') == '' ? 'selected' : '' }} value="" selected
                                            disabled>{{ translate('choose_Brand') }}</option>
                                        @foreach ($brands as $brand)
                                            <option {{ old('brand_id') == $brand['id'] ? 'selected' : '' }}
                                                value="{{ $brand['id'] }}">{{ $brand['defaultName'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-end flex-wrap gap-10">
                            <button class="btn btn-secondary cancel px-4"
                                type="reset">{{ translate('cancel') }}</button>
                            <button id="" class="btn btn--primary text-white">{{ translate('update') }}</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="tab-content">
        <div class="tab-pane fade show active" id="product">
            <div class="row">
                <div class="col-md-12">
                    <div class="card h-100">
                        <div class="px-3 py-4">
                            <div class="row align-items-center">
                                <div class="col-md-4 col-lg-4 mb-2 mb-md-0">
                                    <h5 class="mb-0 text-capitalize d-flex gap-2">
                                        {{ translate('subscription_history') }}
                                    </h5>
                                </div>
                                <div class="col-md-8 col-lg-8">
                                    <div class="row gy-2 gx-2 align-items-center text-left">
                                        <div class="col-sm-12 col-md-8">
                                            <form action="{{ url()->current() }}" method="GET">
                                                <div class="row gy-2 gx-2 align-items-center text-left">
                                                    <div class="col-sm-12 col-md-4">

                                                        <select
                                                            class="js-select2-custom form-control action-get-billing-types"
                                                            name="subscription_plan_id" id="subscription-plan-select"
                                                            data-url-prefix="{{ route('admin.subscriptions.get-billing-types') . '?plan_id=' }}"
                                                            data-element-id="billing-type-select"
                                                            data-element-type="select">
                                                            <option
                                                                value="{{ request('subscription_plan_id') != null ? request('subscription_plan_id') : null }}"
                                                                {{ old('subscription_plan_id') == '' ? 'selected' : '' }}
                                                                disabled>
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
                                                    <div class="col-sm-12 col-md-3">
                                                        <button type="submit"
                                                            class="btn btn--primary px-4 w-100 text-nowrap">
                                                            {{ translate('filter') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive datatable-custom">
                            <table id="columnSearchDatatable"
                                style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};"
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                <thead class="thead-light thead-50 text-capitalize">
                                    <tr>
                                        <th>{{ translate('SL') }}</th>
                                        <th>{{ translate('subscription_plan') }}</th>
                                        <th class="text-center">{{ translate('status') }}</th>
                                        <th class="text-center">{{ translate('action') }}</th>
                                    </tr>
                                </thead>

                                <tbody id="set-rows">
                                    @foreach ($subscriptionHistory as $key => $plan)
                                        <tr>
                                            <th scope="row">{{ $key + 1 }}</th>
                                            <td>
                                                {{ $plan->plan?->name ?? '-' }}
                                            </td>
                                            <td class="text-center">
                                                @if ($seller->status == 'approved')
                                                    <label
                                                        class="badge badge-soft-primary">{{ translate('active') }}</label>
                                                @else
                                                    <label
                                                        class="badge badge-soft-danger">{{ translate('inactive') }}</label>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a class="btn btn-outline-info btn-sm square-btn"
                                                        title="{{ translate('view') }}"
                                                        href="#subscriptionDetailModal{{ $plan->id }}"
                                                        data-toggle="modal"
                                                        data-target="#subscriptionDetailModal{{ $plan->id }}">
                                                        <i class="tio-invisible"></i>
                                                    </a>

                                                    <form action="{{ route('admin.sellers.subscription.cancel') }}"
                                                        method="post">
                                                        @csrf

                                                        <input type="hidden" name="subscription_plan"
                                                            value="{{ $plan->plan?->id }}">
                                                        <input type="hidden" name="seller"
                                                            value="{{ $seller->id }}">

                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if (count($subscriptionHistory) == 0)
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
    </div> --}}
    </div>

    {{-- @foreach ($subscriptionHistory as $key => $plan)
        @include('layouts.front-end.partials.modal._vendor-subscription-detail', ['plan' => $plan])
    @endforeach --}}


    <!-- Subscrition Plan Modal -->
    <div class="modal fade __modal" id="subscription-modal">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <h3 class="modal-title text-center">{{ translate('Change_Subscription_Plan') }}</h3>
                <!-- Modal body -->
                <div class="modal-body overflow-hidden">
                    <div class="plan-slider owl-theme owl-carousel">
                        @forelse ($subscriptionPlans as $package)
                            <div class="__plan-item">
                                <!-- Active Plan Check -->
                                <input type="radio" name="package_id" value="{{ $package->id }}" id="basic"
                                    {{ isset($seller_subscription) && $seller_subscription->plan_id == $package->id ? 'checked' : '' }}
                                    hidden>
                                <div class="__plan">
                                    <div class="plan-header">
                                        <h3 class="title">
                                            <span id="div_one_{{ $package->id }}">{{ $package->name }}</span>
                                            <img class="check-plan-icon"
                                                src="{{ dynamicAsset('/public/assets/front-end/img/check3.svg') }}"
                                                alt="">
                                        </h3>
                                        {{-- <div class="duration">
                                            <strong>{{ translate('fee') }} /</strong><span>{{ '30' }}
                                                {{ translate('days') }}</span>
                                        </div> --}}
                                        {{-- <h2 class="price">{{ 'price here' }}</h2> --}}
                                        <br>
                                    </div>
                                    <ul class="plan-info">
                                        <li>
                                            <img class="plan-info-icon" src="{{dynamicAsset('/public/assets/front-end/img/check2.svg')}}" alt=""> {{ $package->max_product_lifecycle }} {{ translate('days_product_lifecycle') }}
                                        </li>
                                        @if ($package->max_product_upload == 'unlimited')
                                            <li>
                                                <img class="plan-info-icon" src="{{dynamicAsset('/public/assets/front-end/img/check2.svg')}}" alt=""> {{ translate('messages.Unlimited_uploads') }}
                                            </li>
                                        @else
                                            <li>
                                                <img class="plan-info-icon" src="{{dynamicAsset('/public/assets/front-end/img/check2.svg')}}" alt=""> {{ $package->max_product_upload }} {{ translate('product_uploads') }}
                                            </li>
                                        @endif

                                        @if ($package->discount)
                                            <li>
                                                <img class="plan-info-icon" src="{{dynamicAsset('/public/assets/front-end/img/check2.svg')}}" alt=""> {{ translate('discount') }}
                                            </li>
                                        @endif
                                        @if ($package->product_top_search)
                                            <li>
                                                <img class="plan-info-icon" src="{{dynamicAsset('/public/assets/front-end/img/check2.svg')}}" alt=""> {{ translate('product_top_search') }}
                                            </li>
                                        @endif
                                        @if ($package->item_verification)
                                            <li>
                                                <img class="plan-info-icon" src="{{dynamicAsset('/public/assets/front-end/img/check2.svg')}}" alt=""> {{ translate('item_verification') }}
                                            </li>
                                        @endif
                                        @if ($package->product_photoshoot)
                                            <li>
                                                <img class="plan-info-icon" src="{{dynamicAsset('/public/assets/front-end/img/check2.svg')}}" alt=""> {{ translate('product_photoshoot') }}
                                            </li>
                                        @endif
                                        @if ($package->free_delivery)
                                            <li>
                                                <img class="plan-info-icon" src="{{dynamicAsset('/public/assets/front-end/img/check2.svg')}}" alt=""> {{ translate('free_delivery') }}
                                            </li>
                                        @endif
                                    </ul>
                                    <div class="text-center">
                                        @if (isset($seller_subscription) && $seller_subscription->plan_id == $package->id)
                                            <button data-id="{{ $package->id }}" data-target="#package_detail"
                                                id="package_detail" type="button"
                                                class="btn btn--warning text-white renew-btn package_detail">
                                                {{ translate('Renew') }}</button>
                                        @else
                                            <button data-id="{{ $package->id }}" data-target="#package_detail"
                                                id="package_detail" type="button"
                                                class="btn btn--primary shift-btn package_detail">{{ translate('Shift_in_this_plan') }}</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="img-responsive center-block d-block mx-auto">
                                <img src="{{ dynamicAsset('public/assets/back-end/svg/illustrations/sorry.svg') }}" alt="public">
                                <h4>
                                    {{ translate('No_subscription_plan_available') }}
                                </h4>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscrition Plan Modal 2 -->
    <div class="modal" id="subscription-renew-modal">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="data_package" id="data_package">
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="myInput" value="{{ $seller->id }}">

@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#isTrialPlan').change(
                function() {
                    if (this.checked) {
                        $('.plan-selection-select').hide();
                        $('#subscription-select').attr('required', false);
                        $('#billing-type-select').attr('required', false);
                    } else {
                        $('.plan-selection-select').show();
                        $('#subscription-select').attr('required', true);
                        $('#billing-type-select').attr('required', true);
                    }
                }
            );

            if ($('#isTrialPlan').is(':checked')) {
                $('.plan-selection-select').hide();
                $('#subscription-select').attr('required', false);
                $('#billing-type-select').attr('required', false);
            } else {
                $('.plan-selection-select').show();
                $('#subscription-select').attr('required', true);
                $('#billing-type-select').attr('required', true);
            }
        })
    </script>

    <script>
        $(document).ready(function() {
            $('#isVendorExclusiveBrand').change(
                function() {
                    if (this.checked) {
                        $('.seller-brand-selection').removeClass('d-none');
                        $('#brand-selector').attr('required', true);
                        // $('.subscription-plan-card').addClass('d-none');
                    } else {
                        $('.seller-brand-selection').addClass('d-none');
                        $('#brand-selector').attr('required', false);
                        // $('.subscription-plan-card').removeClass('d-none');
                    }
                }
            );

            if ($('#isVendorExclusiveBrand').is(':checked')) {
                $('.seller-brand-selection').removeClass('d-none');
                $('#brand-selector').attr('required', true);
                // $('.subscription-plan-card').addClass('d-none');
            } else {
                // $('.subscription-plan-card').removeClass('d-none');
                $('#brand-selector').attr('required', false);
            }
        })
    </script>

    <script>
        $('#subscription-update').on('click', function() {
            $('#subscription-card').slideToggle();
        });

        $('.cancel').on('click', function() {
            $('.subscription_form').attr('action', $('#route-admin-banner-store').data('url'));
            $('#subscription-card').slideToggle();
        });
    </script>

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

@push('script_2')
    <script src="{{asset('public/assets/front-end/js/owl.carousel.min.js')}}"></script>
    <script>
        "use strict";
        $(document).on('click', '.package_detail', function () {
            let id = $(this).attr('data-id');
            let seller_id = $("#myInput").val();

            $.ajax({
                url: '{{url('/')}}/admin/sellers/subscription/package_selected/'+id+'/'+seller_id,
                method: 'get',
                beforeSend: function() {
                            $('#loading').show();
                            $('#subscription-modal').modal('hide')
                            },
                success: function(data){
                    $('#data_package').html(data.view);
                    $('#subscription-renew-modal').modal('show')
                },
                complete: function() {
                        $('#loading').hide();
                    },

            });
        });

        $('#package_selected').on('submit', function() {
            let formData = new FormData(this);
            console.log('working');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    $('#data_package').html(data.view);
                    $('#itemCount').html(data.total);
                    // $('.page-area').hide();
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        });

        //Check Data
        $("input[name='package_id']").each(function(){
            if($(this).is(':checked')) {

                $('.__plan-item').find('.shift-btn').show()
                $('.__plan-item').find('.renew-btn').hide()

                $(this).closest('.__plan-item').addClass('active')
                $(this).closest('.__plan-item').find('.shift-btn').hide()
                $(this).closest('.__plan-item').find('.renew-btn').show()

                $($(this)).on('click', function(){
                    $('#subscription-modal').modal('hide')
                    $('#subscription-renew-modal').modal('show')
                })

            } else {
                $($(this)).on('click', function(){
                    $('#subscription-modal').modal('hide')
                    $('#subscription-change-plan-modal').modal('show')

                })
            }
        })

        // Plan Slider
        $('.plan-slider').owlCarousel({
            // center: true,
            loop: false,
            margin: 30,
            responsiveClass:true,
            nav:false,
            dots:false,
            items: 3,
            autoplay: true,
            autoplayTimeout:1500,
            autoplayHoverPause:true,
            rtl: {{ Session::get('direction') === 'rtl' ? 'true' : 'false'}},
            responsive:{
                0: {
                    items:1.1,
                    margin: 10,
                },
                375: {
                    items: 1.2,
                    margin: 30,
                },
                576: {
                    items:2.2,
                },
                768: {
                    items:2.2,
                    margin: 20,
                },
                992: {
                    items: 3,
                    margin: 30,
                },
                1200: {
                    items: 3,
                    margin: 37,
                }
            }
        })

        function status_change_alert(url, message, e) {
            e.preventDefault();
            Swal.fire({
                title: '{{ translate('Are_you_sure?') }}',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('no') }}',
                confirmButtonText: '{{ translate('yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href=url;
                }
            })
        }
    </script>
@endpush
