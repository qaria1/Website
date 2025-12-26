@extends('layouts.back-end.app')

@section('title', translate('subscription_plan'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                {{ translate('subscription_plan_update') }}
            </h2>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.business-settings.subscription.update', [$plan->id]) }}"
                            class="text-start" method="post">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            <div class="form-group">
                                <div class="row ">
                                    <div class="col-md-12">
                                        <label class="title-color" for="title">{{ translate('plan_name') }}</label>
                                        <input type="text" name="plan_name" value="{{ $plan->name }}"
                                            class="form-control" placeholder="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row ">
                                    <div class="col-md-12">
                                        <label class="title-color" for="code">{{ translate('plan_code') }}</label>
                                        <input type="text" name="plan_code" value="{{ $plan->code }}"
                                            class="form-control" placeholder="">
                                    </div>
                                </div>
                            </div>

                            @foreach ($plan->features as $feature)
                                <div class="form-group">
                                    <div class="row ">
                                        <div class="col-md-12">
                                            <label class="title-color" for="{{ $feature->name }}">
                                                {{ translate($feature->name) }}

                                                @if ($feature->name === 'max_product_lifetime')
                                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                                        data-placement="right"
                                                        title="{{ translate('please_make_sure_to_input_the_value_in_days') }}">
                                                        <img width="16"
                                                            src="{{ asset('/public/assets/back-end/img/info-circle.svg') }}"
                                                            alt="">
                                                    </span>
                                                @endif
                                            </label>
                                            <input type="number" name="features[{{ $feature->id }}]"
                                                value="{{ $feature->pivot->value }}" class="form-control" placeholder="">
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="form-group">
                                <div class="row ">
                                    <div class="col-md-12">
                                        <label class="title-color" for="max_product_upload">
                                            {{ translate('maximum_product_upload') }}
                                        </label>
                                        <input type="number" name="max_product_upload"
                                            value="{{ $plan->max_product_upload }}" class="form-control" placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row ">
                                    <div class="col-md-12">
                                        <label class="title-color" for="max_product_lifecycle">
                                            {{ translate('maximum_product_lifecycle') }}
                                            <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                                data-placement="right"
                                                title="{{ translate('please_make_sure_to_input_the_value_in_days') }}">
                                                <img width="16"
                                                    src="{{ asset('/public/assets/back-end/img/info-circle.svg') }}"
                                                    alt="">
                                            </span>
                                        </label>
                                        <input type="number" name="max_product_lifecycle"
                                            value="{{ $plan->max_product_lifecycle }}" class="form-control" placeholder="">
                                    </div>
                                </div>
                            </div>

                            @if ($plan->slug == 'free')
                                <div class="form-group">
                                    <div class="row ">
                                        <div class="col-md-12">
                                            <label class="title-color" for="available_vendors">
                                                {{ translate('available_vendors_count') }}
                                            </label>
                                            <input type="number" name="available_vendors"
                                                value="{{ $plan->available_vendors }}" class="form-control" placeholder="">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="d-flex gap-10 flex-wrap justify-content-end">
                                <a href="{{ route('admin.business-settings.subscription.index') }}" class="btn px-4">{{ translate('back') }}</a>
                                <button type="submit" class="btn btn--primary px-4">{{ translate('update') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
