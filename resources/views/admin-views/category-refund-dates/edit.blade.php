@extends('layouts.back-end.app')

@section('title', translate('delivery_class_update'))

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
            <h2 class="h1 mb-0">
                <img src="{{ asset('public/assets/back-end/img/brand-setup.png') }}" class="mb-1 mr-1" alt="">
                {{ translate('delivery_class_update') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body text-start">
                        <form action="{{ route('admin.category-refund-dates.update', [$categoryRefundDate['id']]) }}"
                            method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group form-system-language-form">

                                        <div class="form-group">
                                            <label class="title-color">
                                                {{ translate('category_number_of_days') }}
                                            </label>
                                            <input type="text" name="number_of_days"
                                                value="{{ $categoryRefundDate['number_of_days'] }}" class="form-control"
                                                placeholder="{{ translate('new_category_number_days') }}">
                                        </div>

                                        <input type="hidden" name="id" value="{{ $categoryRefundDate['id'] }}">
                                    </div>


                                    {{-- @if (theme_root_path() != 'theme_aster') --}}
                                    <div class="d-flex justify-content-end gap-3">
                                        <button type="reset" id="reset" class="btn btn-secondary px-4">
                                            {{ translate('reset') }}
                                        </button>
                                        <button type="submit" class="btn btn--primary px-4">
                                            {{ translate('update') }}
                                        </button>
                                    </div>
                                    {{-- @endif --}}
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
