@extends('layouts.back-end.app')

@section('title', translate('exclusive_Brand_List'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 d-flex gap-2">
                <img width="20" src="{{ asset('public/assets/back-end/img/brand.png') }}" alt="">
                {{ translate('exclusive_Brand_List') }}
                <span class="badge badge-soft-dark radius-50 fz-14">{{ $shops->total() }}</span>
            </h2>
            <p class="text-muted mt-2 mb-0">{{ translate('exclusive_brand_help_text') }}</p>
        </div>

        <div class="row mt-20">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row g-2 flex-grow-1">
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-custom input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                               placeholder="{{ translate('search_by_shop_name') }}" aria-label="{{ translate('search_by_shop_name') }}" value="{{ request('searchValue') }}">
                                        <button type="submit" class="btn btn--primary input-group-text">{{ translate('search') }}</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-4 col-md-6 col-lg-8 d-flex justify-content-end">
                                <div class="btn-group">
                                    <a href="{{ route('admin.sellers.exclusive-brand.list') }}" class="btn {{ request('filter') != 'exclusive' ? 'btn--primary' : 'btn-outline--primary' }} text-capitalize">{{ translate('all_shops') }}</a>
                                    <a href="{{ route('admin.sellers.exclusive-brand.list', ['filter' => 'exclusive']) }}" class="btn {{ request('filter') == 'exclusive' ? 'btn--primary' : 'btn-outline--primary' }} text-capitalize">{{ translate('exclusive_brands') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 text-start">
                                <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{ translate('SL') }}</th>
                                    <th>{{ translate('shop_banner') }}</th>
                                    <th>{{ translate('shop') }}</th>
                                    <th class="text-center">{{ translate('vendor_Name') }}</th>
                                    <th class="text-center">{{ translate('total_Product') }}</th>
                                    <th class="text-center">{{ translate('exclusive_brand') }}</th>
                                </tr>
                                </thead>
                                <tbody>

                                @php
                                    $counter=0;
                                @endphp
                                @foreach($shops as $shop)
                                    <tr>
                                        <td>{{ ++$counter }}</td>
                                        <td>
                                            <div class="avatar-60 d-flex align-items-center rounded overflow-hidden">
                                                <img class="img-fluid" alt=""
                                                     src="{{ getValidImage(path: 'storage/app/public/shop/banner/'.$shop->banner, type: 'backend-brand') }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img class="rounded-circle" width="32" alt=""
                                                     src="{{ getValidImage(path: 'storage/app/public/shop/'.$shop->image, type: 'shop') }}">
                                                <span class="font-weight-bold">{{ $shop->name }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if($shop?->seller)
                                                {{ $shop->seller->f_name . ' ' . $shop->seller->l_name }}
                                            @else
                                                <span class="badge badge-soft-warning">{{ translate('no_vendor') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $shop->products_count }}</td>
                                        <td>
                                            <form action="{{ route('admin.sellers.exclusive-brand.update-status') }}" method="post" id="exclusive-brand-status{{$shop->id}}-form">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $shop->id }}">
                                                <input type="hidden" name="is_exclusive_brand" value="{{ $shop->is_exclusive_brand ? 0 : 1 }}">
                                                <label class="switcher mx-auto">
                                                    <input type="checkbox" class="switcher_input exclusive-brand-toggle" name="status"
                                                           id="exclusive-brand-status{{ $shop->id }}"
                                                           value="{{ $shop->is_exclusive_brand ? 0 : 1 }}"
                                                           {{ $shop->is_exclusive_brand ? 'checked' : '' }}
                                                           data-toggle-id="exclusive-brand-status{{ $shop->id }}"
                                                           data-form-id="exclusive-brand-status{{ $shop->id }}-form">
                                                    <span class="switcher_control"></span>
                                                </label>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="table-responsive mt-4">
                        <div class="d-flex justify-content-lg-end">
                            {{ $shops->links() }}
                        </div>
                    </div>
                    @if(count($shops)==0)
                        <div class="text-center p-4">
                            <img class="mb-3 w-160" src="{{ asset('public/assets/back-end/svg/illustrations/sorry.svg') }}" alt="">
                            <p class="mb-0">{{ translate('no_data_to_show') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <span id="route-admin-exclusive-brand-status" data-url="{{ route('admin.sellers.exclusive-brand.update-status') }}"></span>
@endsection

@push('script')
    <script>
        $(document).on('change', '.exclusive-brand-toggle', function () {
            var formId = $(this).data('form-id');
            var form = $('#' + formId);
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function (response) {
                    if (response.success) {
                        toastr.success(response.message, {timeOut: 3000});
                    }
                },
                error: function (xhr) {
                    var msg = xhr.responseJSON?.message || "{{ translate('something_went_wrong') }}";
                    toastr.error(msg, {timeOut: 3000});
                    location.reload();
                }
            });
        });
    </script>
@endpush
