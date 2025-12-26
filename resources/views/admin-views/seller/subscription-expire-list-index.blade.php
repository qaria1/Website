@extends('layouts.back-end.app')

@section('title', translate('vendor_expire_list'))
@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <div class="page-title-wrap d-flex justify-content-between flex-wrap align-items-center gap-3 mb-3">
                <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                    <img src="{{asset('/public/assets/back-end/img/add-new-seller.png')}}" alt="">
                    {{translate('subscription_expire_list')}}
                </h2>
                {{-- <a href="{{route('admin.sellers.waiting-list.add')}}" class="btn btn--primary">+ {{translate('add_seller')}}</a> --}}
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ url()->current() }}" method="GET">
                    <input type="hidden" value="{{ request('status') }}" name="">
                    <div class="row gx-2">
                        <div class="col-12">
                            <h4 class="mb-3">{{ translate('filter_vendors') }}</h4>
                        </div>

                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label for="name" class="title-color">{{ translate('subscription_plan') }}</label>
                                <select class="js-select2-custom form-control action-get-billing-types"
                                    name="subscription_plan_id" id="subscription-plan-select" data-url-prefix="{{ route('admin.subscriptions.get-billing-types') . '?plan_id=' }}"
                                    data-element-id="billing-type-select" data-element-type="select">
                                    <option
                                        value="{{ request('subscription_plan_id') != null ? request('subscription_plan_id') : null }}"
                                        {{ old('subscription_plan_id') == "" ? 'selected' : '' }} disabled>
                                        {{ translate('select_plan') }}
                                    </option>

                                    @forelse ($subscriptionPlans as $plan)
                                        <option value="{{ $plan->id }}" {{ old('subscription_plan_id') == $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label for="name" class="title-color">{{ translate('billing_type') }}</label>
                                <select class="js-select2-custom form-control"
                                    name="billing_id" id="billing-type-select" data-element-type="select">
                                    <option
                                        value="{{ request('billing_id') != null ? request('billing_id') : null }}"
                                        selected {{ request('billing_id') != null ? '' : 'disabled' }}>
                                        {{ translate('select_subscription_first') }}
                                    </option>

                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex gap-3 justify-content-end">
                                <a href="{{ route('admin.sellers.subscription-due-expire.index') }}"
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
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="p-3">
                        <div class="row gy-1 align-items-center justify-content-between">
                            <div class="col-auto">
                                <h5>
                                {{ translate('total')}}
                                    <span class="badge badge-soft-dark radius-50 fz-12 ml-1"> {{ $planExpireSellers->total() }}</span>
                                </h5>
                            </div>
                            {{-- <div class="col-auto">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-custom input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                               placeholder="{{translate('search_Seller_Name')}}" aria-label="Search sellers"
                                               value="{{ request('searchValue') }}" required>
                                        <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                                    </div>
                                </form>
                            </div> --}}
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable"
                                style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{translate('SL')}}</th>
                                <th>{{translate('vendor_name')}}</th>
                                <th>{{translate('vendor_contact')}}</th>
                                <th>{{translate('days_left')}}</th>
                                <th>{{translate('subscription')}}</th>
                                <th>{{translate('billing_type')}}</th>
                                <th>{{translate('trial')}}</th>
                                <th>{{translate('total_product_uploaded')}}</th>
                                <th>{{translate('total_product_left')}}</th>
                                <th class="text-center">{{translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($planExpireSellers as $key => $seller)
                                <tr>
                                    <td>{{$planExpireSellers->firstitem() + $key}}</td>
                                    <td>{{$seller?->seller?->f_name.' '.$seller?->seller?->l_name ?? '-'}}</td>
                                    <td>{{$seller?->seller?->phone ?? '-'}}</td>
                                    <td class="text-center">
                                        <span class="badge badge-soft-danger" style="font-size: 14px;">{{$seller->days_left ?? '-'}}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-soft-secondary">{{$seller?->plan?->name ?? '-'}}</span>
                                    </td>
                                    <td class="text-center">
                                        {{ $seller?->billingType?->name ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        @if ($seller->is_trial == 1)
                                            <span class="badge badge-soft-primary">{{ translate('yes') }}</span>
                                        @else
                                            <span class="badge badge-soft-warning">{{ translate('no') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ $seller?->seller?->getTotalProductForActivePlan($seller?->seller) ?? '-' }}
                                    </td>

                                    <td class="text-center">
                                        {{ (int)$seller?->max_product_upload - (int)$seller?->seller?->getTotalProductForActivePlan($seller?->seller) ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a title="{{translate('view')}}"
                                                class="btn btn-outline-info btn-sm"
                                                href="{{route('admin.sellers.view',$seller?->seller_id)}}">
                                                <i class="tio-invisible"></i>
                                                {{ translate('view_vendor') }}
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if(count($planExpireSellers)==0)
                            <div class="text-center p-4">
                                <img class="mb-3 w-160"
                                        src="{{asset('public/assets/back-end/svg/illustrations/sorry.svg')}}"
                                        alt="{{translate('image_description')}}">
                                <p class="mb-0">{{translate('no_data_to_show')}}</p>
                            </div>
                       @endif
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-center justify-content-md-end">
                            {{$planExpireSellers->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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