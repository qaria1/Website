@extends('layouts.back-end.app')

@section('title', translate('add_billing_type'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3 d-flex justify-content-between">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                {{ translate('add_billing_type_for') }}<u><strong>{{ $plan->name }}</strong></u> {{ translate('plan') }}
            </h2>
            <a href="{{ route('admin.business-settings.subscription.index') }}" class="">{{ translate('go_back') }}</a>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.business-settings.subscription.billing-type.store') }}" class=""
                            method="post">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">

                            <div class="row">
                                <div class="col-md-6">
                                    <div>
                                        <label for="billingType">{{ translate('billing_type') }}</label>
                                        <select name="billing_type" class="js-select2-custom form-control" id="billingType"
                                            required>
                                            <option disabled selected value="">{{ translate('select_billing_type') }}
                                            </option>
                                            @foreach ($billingTypeArray as $key => $billing)
                                                <option value="{{ $billing->id }}">{{ $billing->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div>
                                        <label for="billingPrice">{{ translate('billing_price') }}:
                                            ({{ translate('in') . ' ' . getCurrencyCode(type: 'default') }})</label>
                                        <input id="billingPrice" type="number" placeholder="{{ translate('price') }}"
                                            required name="billing_price" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="d-flex justify-content-end gap-10">
                                    <button type="submit" class="btn btn--primary px-5">{{ translate('add') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="border-bottom px-4 py-3">
                <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2 text-capitalize">
                    {{ translate('added_billing_types_list_for') }}:
                    <u><strong>{{ $plan->name }}</strong></u>{{ translate('subscription_plan') }}
                </h5>
            </div>
            <div class="table-responsive pb-3">
                <table
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                    style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
                    <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{ translate('#') }}</th>
                            <th>{{ translate('name') }}</th>
                            {{-- <th>{{ translate('description') }}</th> --}}
                            <th>{{ translate('price') }}</th>
                            {{-- <th>{{ translate('duration') }}</th> --}}
                            {{-- <th class="text-center">{{ translate('status') }}</th> --}}
                            <th class="text-center">{{ translate('action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($assignedBillingTypes as $key => $billing)
                            <tr>
                                <th>{{ $key + 1 }}</th>
                                <td>{{ $billing->name ?? '-' }}</td>
                                {{-- <td>
                                {{ $billing->description ?? '-' }}
                            </td> --}}
                                <td>
                                    {{ $billing->price ?? '-' }}
                                </td>
                                {{-- <td>
                                {{ $billing->duration_in_days ?? '-' }}
                            </td> --}}
                                {{-- <td>
                                <form action="{{ route('admin.business-settings.shipping-method.update-status') }}"
                                    method="post" id="">
                                    @csrf
                                    <input type="hidden" name="id" value="">
                                    <label class="switcher mx-auto">
                                        <input type="checkbox" class="switcher_input toggle-switch-message"
                                            id="shipping-methods" name="status" value="1"

                                            data-modal-id = "toggle-status-modal"
                                            data-toggle-id = "shipping-methods"
                                            data-on-image = "category-status-on.png"
                                            data-off-image = "category-status-off.png"
                                            data-on-title = "{{ translate('want_to_Turn_ON_This_Shipping_Method') . '?' }}"
                                            data-off-title = "{{ translate('want_to_Turn_OFF_This_Shipping_Method') . '?' }}"
                                            data-on-message = "<p>{{ translate('if_you_enable_this_shipping_method_will_be_shown_in_the_user_app_and_website_for_customer_checkout') }}</p>"
                                            data-off-message = "<p>{{ translate('if_you_disable_this_shipping_method_will_not_be_shown_in_the_user_app_and_website_for_customer_checkout') }}</p>">
                                        <span class="switcher_control"></span>
                                    </label>
                                </form>
                            </td> --}}
                                <td>
                                    <div class="d-flex flex-wrap justify-content-center gap-10">
                                        <a class="btn btn-outline--primary btn-sm edit" title="{{ translate('edit') }}"
                                            href="{{ route('admin.business-settings.billing-type.update', [$billing->id]) }}">
                                            <i class="tio-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if (count($assignedBillingTypes) == 0)
                <div class="text-center p-4">
                    <img class="mb-3 w-160"
                        src="{{ asset('public/assets/back-end/svg/illustrations/sorry.svg') }}"
                        alt="{{ translate('image_description') }}">
                    <p class="mb-0">{{ translate('no_data_to_show') }}</p>
                </div>
            @endif
        </div>
    @endsection
