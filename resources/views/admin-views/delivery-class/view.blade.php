@extends('layouts.back-end.app')

@section('title', translate('delivery_class'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 d-flex gap-10">
                <img src="{{ asset('public/assets/back-end/img/brand-setup.png') }}" alt="">
                {{ translate('delivery_class_Setup') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body text-start">
                        <form action="{{ route('admin.delivery-class.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <ul class="nav nav-tabs w-fit-content mb-4">
                                @foreach ($languages as $lang)
                                    <li class="nav-item text-capitalize">
                                        <span
                                            class="nav-link form-system-language-tab cursor-pointer {{ $lang == $defaultLanguage ? 'active' : '' }}"
                                            id="{{ $lang }}-link">
                                            {{ ucfirst(getLanguageName($lang)) . '(' . strtoupper($lang) . ')' }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div>
                                        @foreach ($languages as $lang)
                                            <div class="form-group {{ $lang != $defaultLanguage ? 'd-none' : '' }} form-system-language-form"
                                                id="{{ $lang }}-form">
                                                <div class="form-group" id="{{ $lang }}-form">
                                                    <label class="title-color">{{ translate('Delivery_Class_Name') }}<span
                                                            class="text-danger">*</span> ({{ strtoupper($lang) }})</label>
                                                    <input type="text" name="name[]" class="form-control"
                                                        placeholder="{{ translate('new_delivery_Class_Name') }}"
                                                        {{ $lang == $defaultLanguage ? 'required' : '' }}>
                                                </div>
                                                <div class="form-group" id="{{ $lang }}-form">
                                                    <label class="title-color">{{ translate('delivery_Code') }}<span
                                                            class="text-danger">*</span> ({{ strtoupper($lang) }})</label>
                                                    <input type="text" name="code[]" class="form-control"
                                                        placeholder="{{ translate('new_delivery_Code') }}"
                                                        {{ $lang == $defaultLanguage ? 'required' : '' }}>
                                                </div>
                                                <div class="form-group" id="{{ $lang }}-form">
                                                    <label class="title-color">{{ translate('delivery_Description') }}<span
                                                            class="text-danger">*</span> ({{ strtoupper($lang) }})</label>
                                                    <input type="text" name="description[]" class="form-control"
                                                        placeholder="{{ translate('new_delivery_Description') }}"
                                                        {{ $lang == $defaultLanguage ? 'required' : '' }}>
                                                </div>
                                            </div>
                                            <input type="hidden" name="lang[]" value="{{ $lang }}">
                                        @endforeach
                                        <input name="position" value="0" class="d-none">
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
                                    {{ translate('delivery_class_list') }}
                                    <span
                                        class="badge badge-soft-dark radius-50 fz-12">{{ $delivery_classes->total() }}</span>
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
                                    <th>{{ translate('name') }}</th>
                                    <th>{{ translate('delivery_code') }}</th>
                                    <th>{{ translate('description') }}</th>
                                    <th class="text-center">{{ translate('action') }}</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($delivery_classes as $key => $category)
                                    <tr>
                                        <td>{{ $category['id'] }}</td>
                                        <td>{{ $category['defaultname'] }}</td>
                                        <td>{{ $category['code'] }}</td>
                                        <td>
                                            {{ $category['description'] }}
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-10">
                                                <a class="btn btn-outline-info btn-sm square-btn"
                                                    title="{{ translate('edit') }}"
                                                    href="{{ route('admin.delivery-class.edit', [$category['id']]) }}">
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
                            {{ $delivery_classes->links() }}
                        </div>
                    </div>

                    @if (count($delivery_classes) == 0)
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
