@extends('layouts.front-end.app')

@section('title', translate('exclusive_brands'))

@push('css_or_js')
    <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="{{ translate('exclusive_brands') }} - {{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{route('exclusive-shops')}}">
    <meta property="og:description"
          content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">
    <meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="{{ translate('exclusive_brands') }} - {{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{route('exclusive-shops')}}">
    <meta property="twitter:description"
          content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">
    <style>
        body.exclusive-brands-body header {
            position: sticky !important;
            top: 0 !important;
            z-index: 1030 !important;
            background: #fff !important;
        }
        body.exclusive-brands-body .navbar-sticky.navbar-stuck {
            position: static !important;
            box-shadow: none !important;
            background: transparent !important;
        }
        .exclusive-brands-banner-sticky {
            position: sticky;
            top: 0;
            z-index: 1020;
            background: #fff;
        }
        .exclusive-brand-products .row > div {
            flex: 0 0 20%;
            max-width: 20%;
        }
        @media (max-width: 991px) {
            .exclusive-brand-products .row > div {
                flex: 0 0 33.3333%;
                max-width: 33.3333%;
            }
        }
        @media (max-width: 575px) {
            .exclusive-brand-products .row > div {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }
    </style>
@endpush

@section('content')

    @php($decimal_point_settings = getWebConfig(name: 'decimal_point_settings'))

    <div class="exclusive-brands-page container mb-md-4 {{Session::get('direction') === "rtl" ? 'rtl' : ''}} __inline-65">

        <div class="exclusive-brands-banner-sticky shadow-sm">
            <div class="bg-primary-light rounded-0 p-3 p-sm-4" data-bg-img="{{ asset('public/assets/front-end/img/media/bg.png') }}">
                <div class="row g-2 align-items-center">
                    <div class="col-lg-8 col-md-6">
                        <div class="d-flex flex-column gap-1 text-primary">
                            <h4 class="mb-0 text-start fw-bold text-primary text-uppercase">{{ translate('exclusive_brands') }}</h4>
                            <p class="fs-14 fw-semibold mb-0">{{translate('Find_your_favourite_brands')}}</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <form action="{{route('exclusive-shops')}}">
                            <div class="input-group">
                                <input type="text" class="form-control rounded-10" value="{{request('searchValue')}}" placeholder="{{translate('search')}}" name="searchValue">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary rounded-10" type="submit">{{translate('search')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if(count($shops) > 0)
            @foreach ($shops as $shop)
                @php($current_date = date('Y-m-d'))
                @php($start_date = $shop['vacation_start_date'] ? date('Y-m-d', strtotime($shop['vacation_start_date'])) : null)
                @php($end_date = $shop['vacation_end_date'] ? date('Y-m-d', strtotime($shop['vacation_end_date'])) : null)

                <div class="card mb-4 shadow-sm border-0 rounded-10 overflow-hidden exclusive-brand-card">
                    <a href="{{route('shopView',['id'=>$shop['id']])}}" class="text-decoration-none text-reset">
                        <div class="exclusive-brand-banner position-relative">
                            <img class="w-100 exclusive-brand-cover" alt="{{ $shop->name }}"
                                 style="height:180px;object-fit:cover;"
                                 src="{{ getValidImage(path: 'storage/app/public/shop/banner/'.$shop->banner, type: 'shop-banner') }}">
                        </div>
                    </a>
                    <div class="card-body px-3 px-sm-4">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="position-relative">
                                    <div class="overflow-hidden rounded-full exclusive-brand-logo" style="width:64px;height:64px;">
                                        <img class="rounded-full w-100 h-100" style="object-fit:cover;" alt="{{ $shop->name }}"
                                             src="{{ getValidImage(path: 'storage/app/public/shop/'.$shop->image, type: 'shop') }}">
                                    </div>
                                    @if($shop->temporary_close || ($shop->vacation_status && $start_date && $end_date && ($current_date >= $start_date) && ($current_date <= $end_date)))
                                        <span class="temporary-closed position-absolute text-center rounded-full p-2">
                                            <span>{{translate('closed_now')}}</span>
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{route('shopView',['id'=>$shop['id']])}}" class="text-decoration-none text-reset">
                                        <h5 class="mb-1 font-weight-bold">{{ $shop->name }}</h5>
                                    </a>
                                    <div class="d-flex flex-wrap align-items-center gap-2 __text-12px fw-bold web-text-primary">
                                        <span class="text-nowrap">
                                            <i class="tio-star text-warning"></i>
                                            <span class="ml-1">{{ round($shop->average_rating, 1) }}</span>
                                            ({{ $shop->review_count }} {{translate('reviews')}})
                                        </span>
                                        <span class="text-nowrap">{{ $shop->products_count }} {{translate('products')}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $shop->average_rating)
                                        <i class="tio-star text-warning"></i>
                                    @elseif ($shop->average_rating != 0 && $i <= (int)$shop->average_rating + 1 && $shop->average_rating>=((int)$shop->average_rating+.30))
                                        <i class="tio-star-half text-warning"></i>
                                    @else
                                        <i class="tio-star-outlined text-warning"></i>
                                    @endif
                                @endfor
                                <a href="{{route('shopView',['id'=>$shop['id']])}}" class="btn btn--primary btn-sm rounded-10 text-capitalize ml-2">
                                    {{ translate('visit_store') }}
                                </a>
                            </div>
                        </div>

                        @if($shop->featured_products && count($shop->featured_products) > 0)
                            <div class="exclusive-brand-products">
                                <div class="row mt-4 mx-n2">
                                    @foreach($shop->featured_products as $product)
                                        <div class="p-2">
                                            @include('web-views.partials._filter-single-product', ['product'=>$product, 'decimal_point_settings'=>$decimal_point_settings])
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                <a href="{{route('shopView',['id'=>$shop['id']])}}" class="btn btn-outline--primary btn-sm rounded-10 text-capitalize">
                                    {{ translate('view_all_products') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            <div class="row mx-n2">
                <div class="col-md-12">
                    <div class="text-center">
                        {{ $shops->links() }}
                    </div>
                </div>
            </div>
        @else
            <div class="d-flex justify-content-center align-items-center w-100 py-5">
                <div class="text-center">
                    <img src="{{ asset('public/assets/front-end/img/media/product.svg') }}" class="img-fluid" alt="">
                    <h6 class="text-muted mt-3">{{ translate('no_exclusive_brand_found') }}</h6>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('script')
    <script>
        document.body.classList.add('exclusive-brands-body');
        function syncExclusiveBrandsSticky() {
            var header = document.querySelector('header.box-shadow-sm');
            var banner = document.querySelector('.exclusive-brands-banner-sticky');
            if (header && banner) {
                banner.style.top = header.offsetHeight + 'px';
            }
        }
        syncExclusiveBrandsSticky();
        window.addEventListener('resize', syncExclusiveBrandsSticky);
        window.addEventListener('scroll', syncExclusiveBrandsSticky);
    </script>
@endpush
