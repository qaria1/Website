@extends('layouts.back-end.app-seller')
@section('title', translate('subscription_Transactions'))

@section('content')
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
                            {{ isset($seller_subscription) ? ((int)$seller_subscription?->max_product_upload - (int)$seller->getTotalProductForActivePlan($seller)) : '-' }}
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
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <ul class="nav nav-tabs flex-wrap page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ route('vendor.business-settings.subscription.index') }}">{{ translate('subscription_detail') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active"
                            href="{{ route('vendor.business-settings.subscription.transaction') }}">{{ translate('transactions') }}</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card card-body">

            <form action="{{ url()->current() }}" method="GET">
                <div class="row gy-3 align-items-end">
                    {{-- <div class="col-md-4">
                        <div>
                            <label for="status" class="title-color d-flex">{{ translate('choose') }}
                                {{ translate('status') }}</label>
                            <select class="form-control" name="status">
                                <option value="" selected> {{ '---'.translate('select_status').'---' }} </option>
                            </select>
                        </div>
                    </div> --}}
                    <div class="col-md-3">
                        <div>
                            <label for="from" class="title-color d-flex">{{ translate('from') }}</label>
                            <input type="date" name="start_date" id="start-date-time" value="{{ $from }}"
                                   class="form-control"
                                   title="{{ translate('from_date') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div>
                            <label for="to" class="title-color d-flex">{{ translate('to') }}</label>
                            <input type="date" name="end_date" id="end-date-time" value="{{ $to }}"
                                   class="form-control"
                                   title="{{ ucfirst(translate('to_date')) }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div>
                            <button id="filter" type="submit" class="btn btn--primary btn-block filter">
                                <i class="tio-filter-list nav-icon"></i>
                                {{ translate('filter') }}
                            </button>
                        </div>
                    </div>
                    {{-- <div class="col-md-2">
                        <div>
                            <button type="button" class="btn btn-outline--primary text-nowrap btn-block"
                                    data-toggle="dropdown">
                                <i class="tio-download-to"></i>
                                {{ translate('export') }}
                                <i class="tio-chevron-down"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <a class="dropdown-item"
                                       href="">
                                        <img width="14" src="{{asset('/public/assets/back-end/img/excel.png')}}" alt="">
                                        {{ translate('excel') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div> --}}
                </div>
            </form>
        </div>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="order">
                <div class="row pt-2">
                    <div class="col-md-12">
                        <div class="card w-100">
                            <div class="card-header">
                                <h5 class="mb-0">{{ translate('transaction_list') }} <span class="badge badge-soft-dark radius-50 fz-12">{{ $total }}</span></h5>
                            </div>
                            <div class="table-responsive datatable-custom">
                                <table id="datatable"
                                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                    <thead class="thead-light thead-50 text-capitalize">
                                        <tr>
                                            <th>{{ translate('transaction_Id') }}</th>
                                            <th>{{ translate('transaction_Date') }}</th>
                                            <th>{{ translate('package_Name') }}</th>
                                            <th>{{ translate('pricing') }}</th>
                                            <th>{{ translate('duration') }}</th>
                                            <th>{{ translate('payment_method') }}</th>
                                            {{-- <th class="text-center">{{ translate('action') }}</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody id="set-rows">
                                        @foreach ($transactions as $key => $transcation)
                                            <tr class="status class-all">
                                                <td>{{ $transcation->id }}</td>
                                                <td>
                                                    {{ $transcation->created_at->format('d M Y') }}
                                                </td>
                                                <td>
                                                    {{ $transcation?->plan?->name }}
                                                </td>
                                                <td>{{ isset($transcation?->price) ? setCurrencySymbol(amount: usdToDefaultCurrency(amount: $transcation?->price), currencyCode: getCurrencyCode()) : '' }}
                                                </td>
                                                <td>
                                                    {{ $transcation?->validity }} {{ translate('Days') }}
                                                </td>
                                                <td>
                                                    @if ($transcation->payment_method == 'wallet')
                                                        {{ translate('Wallet_payment') }}
                                                    @elseif($transcation->payment_method == 'manual_payment_admin')
                                                        {{ translate('Manual_payment') }}
                                                    @elseif($transcation->payment_method == 'manual_payment_by_restaurant')
                                                        {{ translate('Manual_payment') }}
                                                    @elseif($transcation->payment_method == 'pay_now')
                                                        {{ translate('Digital_Payment') }}
                                                    @else
                                                        {{ translate($transcation->payment_method) }}
                                                    @endif
                                                </td>
                                                {{-- <td>
                                                    <div class="d-flex justify-content-center">
                                                        <a title="{{translate('view')}}"
                                                           class="btn btn-outline-info btn-sm square-btn"
                                                           href=""><i class="tio-invisible"></i>
                                                        </a>
                                                    </div>
                                                </td> --}}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive mt-4">
                                <div class="px-4 d-flex justify-content-lg-end">
                                    {!! $transactions->links() !!}
                                </div>
                            </div>

                            @if (count($transactions) == 0)
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

    <span id="message-select-word" data-text="{{ translate('select') }}"></span>

@endsection
