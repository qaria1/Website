@extends('layouts.back-end.app')

@section('title', translate('billing_type_update'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                {{ translate('billing_type_update') }}
            </h2>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.business-settings.billing-type.update', [$billingType->id]) }}"
                            class="text-start" method="post">
                            @csrf
                            <input type="hidden" name="billing_id" value="{{ $billingType->id }}">
                            <div class="form-group">
                                <div class="row ">
                                    <div class="col-md-12">
                                        <label class="title-color" for="title">{{ translate('billing_name') }}</label>
                                        <input type="text" id="" name="billing_name"
                                            value="{{ $billingType->name }}" class="form-control" placeholder="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="title-color" for="billingDuration">
                                            {{ translate('duration') }}: ({{ translate('in_days') }})
                                            <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                                data-placement="right"
                                                title="{{ translate('please_make_sure_to_input_the_value_in_days') }}">
                                                <img width="16"
                                                    src="{{ asset('/public/assets/back-end/img/info-circle.svg') }}"
                                                    alt="">
                                            </span>
                                        </label>
                                        <input type="text" name="billing_duration"
                                            value="{{ $billingType->duration_in_days }}" class="form-control"
                                            id="billingDuration" placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-10 flex-wrap justify-content-end">
                                <button type="submit" class="btn btn--primary px-4">{{ translate('update') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
