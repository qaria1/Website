@extends('layouts.back-end.app')

@section('title', translate('delivery_class'))
{{-- @dd($categoryRefundDates) --}}
@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 d-flex gap-10">
                <img src="{{ asset('public/assets/back-end/img/brand-setup.png') }}" alt="">
                {{ translate('category_refund_dates_Setup') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body text-start">
                        <form action="{{ route('admin.category-refund-dates.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf


                            <div class="row">
                                <div class="col-lg-6">
                                    <div>
                                        <div class="form-group form-system-language-form">
                                            <div class="form-group">
                                                <label
                                                    class="title-color">{{ translate('Category_refund_dates_Number') }}<span
                                                        class="text-danger"></label>
                                                <input type="text" name="name[]" class="form-control"
                                                    placeholder="{{ translate('new_category_refund_dates_Number') }}">
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="d-flex flex-wrap gap-2 justify-content-end">
                                <button type="reset" id="reset"
                                    class="btn btn-secondary">{{ translate('reset') }}</button>
                                <button type="submit" class="btn btn--primary">{{ translate('submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-20" id="cate-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row align-items-center">
                            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                <h5 class="text-capitalize d-flex gap-1">
                                    {{ translate('category_refund_dates_list') }}
                                    <span
                                        class="badge badge-soft-dark radius-50 fz-12">{{ $categoryRefundDates->total() }}</span>
                                </h5>
                            </div>
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-custom input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="" type="search" name="searchValue" class="form-control"
                                            placeholder="{{ translate('search_here') }}"
                                            value="{{ request('searchValue') }}" required>
                                        <button type="submit" class="btn btn--primary">{{ translate('search') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 text-start">
                            <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{ translate('ID') }}</th>
                                    <th>{{ translate('NUMBER_OF_DAYS') }}</th>
                                    <th class="text-center">{{ translate('action') }}</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categoryRefundDates as $key => $category)
                                    <tr>
                                        <td>{{ $category['id'] }}</td>
                                        <td>{{ $category['number_of_days'] }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-10">
                                                <a class="btn btn-outline-info btn-sm square-btn"
                                                    title="{{ translate('edit') }}"
                                                    href="{{ route('admin.category-refund-dates.edit', [$category['id']]) }}">
                                                    <i class="tio-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="d-flex justify-content-lg-end">
                            {{ $categoryRefundDates->links() }}
                        </div>
                    </div>

                    @if (count($categoryRefundDates) == 0)
                        <div class="text-center p-4">
                            <img class="mb-3 w-160" src="{{ asset('public/assets/back-end/svg/illustrations/sorry.svg') }}"
                                alt="{{ translate('image_description') }}">
                            <p class="mb-0">{{ translate('no_data_found') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script src="{{ asset('public/assets/back-end/js/products-management.js') }}"></script>
@endpush
