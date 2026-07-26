@extends('layouts.back-end.app')

@section('title', translate('add_subscription_plan'))

@section('content')
    <div class="content container-fluid">
        <div class="d-flex align-items-center gap-3 mb-3">
            <a href="{{ route('admin.business-settings.subscription.index') }}"
                class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                <i class="tio-arrow-backward"></i> {{ translate('back') }}
            </a>
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{ asset('/public/assets/back-end/img/business-setup.png') }}" alt="">
                {{ translate('add_new_subscription_plan') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="border-bottom px-4 py-3">
                        <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                            <i class="tio-crown text-primary"></i>
                            {{ translate('plan_details') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.business-settings.subscription.store') }}"
                            class="text-start" method="post">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="title-color form-label" for="plan_name">
                                        {{ translate('plan_name') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="plan_name" id="plan_name"
                                        value="{{ old('plan_name') }}"
                                        class="form-control @error('plan_name') is-invalid @enderror"
                                        placeholder="{{ translate('e.g. Premium, Gold, Basic') }}" required>
                                    @error('plan_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="title-color form-label" for="plan_code">
                                        {{ translate('plan_code') }}
                                        <span class="text-danger">*</span>
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                            data-placement="right"
                                            title="{{ translate('a_short_unique_code_for_the_plan_e.g._P_G_B') }}">
                                            <img width="16" src="{{ asset('/public/assets/back-end/img/info-circle.svg') }}" alt="">
                                        </span>
                                    </label>
                                    <input type="text" name="plan_code" id="plan_code"
                                        value="{{ old('plan_code') }}"
                                        class="form-control @error('plan_code') is-invalid @enderror"
                                        placeholder="{{ translate('e.g. P') }}"
                                        maxlength="10" style="text-transform:uppercase" required>
                                    @error('plan_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="title-color form-label" for="max_product_upload">
                                        {{ translate('maximum_product_upload') }}
                                    </label>
                                    <input type="number" name="max_product_upload" id="max_product_upload"
                                        value="{{ old('max_product_upload', 0) }}"
                                        class="form-control" placeholder="0" min="0">
                                </div>

                                <div class="col-md-6">
                                    <label class="title-color form-label" for="max_product_lifecycle">
                                        {{ translate('maximum_product_lifecycle') }}
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                            data-placement="right"
                                            title="{{ translate('please_make_sure_to_input_the_value_in_days') }}">
                                            <img width="16" src="{{ asset('/public/assets/back-end/img/info-circle.svg') }}" alt="">
                                        </span>
                                    </label>
                                    <input type="number" name="max_product_lifecycle" id="max_product_lifecycle"
                                        value="{{ old('max_product_lifecycle', 0) }}"
                                        class="form-control" placeholder="0" min="0">
                                </div>

                                <div class="col-md-6">
                                    <label class="title-color form-label" for="available_vendors">
                                        {{ translate('available_vendors_count') }}
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                            data-placement="right"
                                            title="{{ translate('leave_0_for_unlimited') }}">
                                            <img width="16" src="{{ asset('/public/assets/back-end/img/info-circle.svg') }}" alt="">
                                        </span>
                                    </label>
                                    <input type="number" name="available_vendors" id="available_vendors"
                                        value="{{ old('available_vendors', 0) }}"
                                        class="form-control" placeholder="0" min="0">
                                </div>
                            </div>

                            {{-- Pricing Section --}}
                            @if(isset($billingTypes) && $billingTypes->count() > 0)
                                <hr class="mt-4 mb-3">
                                <h6 class="text-capitalize mb-1 d-flex align-items-center gap-2">
                                    <i class="tio-money text-primary"></i>
                                    {{ translate('pricing') }}
                                </h6>
                                <p class="text-muted mb-3" style="font-size:13px">
                                    {{ translate('set_the_price_for_each_billing_type_leave_blank_to_skip') }}
                                </p>
                                <div class="row g-3">
                                    @foreach($billingTypes as $billingType)
                                        <div class="col-md-6">
                                            <label class="title-color form-label">
                                                {{ $billingType->name }}
                                                <span class="badge badge-soft-secondary ml-1" style="font-size:11px">
                                                    {{ $billingType->duration_in_days }} {{ translate('days') }}
                                                </span>
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">{{ getCurrencySymbol() }}</span>
                                                </div>
                                                <input
                                                    type="number"
                                                    name="prices[{{ $billingType->id }}]"
                                                    class="form-control"
                                                    placeholder="0.00"
                                                    min="0"
                                                    step="0.01"
                                                    value="{{ old('prices.' . $billingType->id) }}">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-warning mt-3 mb-0">
                                    <i class="tio-info mr-1"></i>
                                    {{ translate('no_billing_types_found_please_add_billing_types_first_from_billing_types_settings') }}
                                </div>
                            @endif

                            <div class="d-flex gap-2 flex-wrap justify-content-end mt-4">
                                <a href="{{ route('admin.business-settings.subscription.index') }}"
                                    class="btn px-4">{{ translate('cancel') }}</a>
                                <button type="submit" class="btn btn--primary px-4">
                                    <i class="tio-add mr-1"></i> {{ translate('create_plan') }}
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 bg-soft-primary">
                    <div class="card-body">
                        <h6 class="text-primary d-flex align-items-center gap-2 mb-3">
                            <i class="tio-info"></i> {{ translate('note') }}
                        </h6>
                        <ul class="pl-3 mb-0 text-muted" style="font-size:13px">
                            <li class="mb-2">{{ translate('after_creating_the_plan_you_can_add_billing_types_to_it') }}</li>
                            <li class="mb-2">{{ translate('the_plan_code_should_be_unique_and_short') }}</li>
                            <li class="mb-2">{{ translate('set_available_vendors_to_0_for_unlimited_slots') }}</li>
                            <li>{{ translate('you_can_edit_the_plan_later_using_the_edit_button') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
