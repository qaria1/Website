<div class="card-header">
    <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
        <img width="20" src="{{ asset('/public/assets/back-end/img/top-selling-product.png') }}" alt="">
        {{ translate('wishlisted_Products') }}
    </h4>
</div>

<div class="card-body">
    @if ($wishlistProducts)
        <div class="grid-card-wrap">
            @foreach ($wishlistProducts as $key => $wishlist)
                <div class="cursor-pointer grid-card basic-box-shadow"
                    onclick="location.href='{{ route('vendor.products.view', [$wishlist['id']]) }}'">
                    <div class="">
                        <img class="avatar avatar-bordered border-gold avatar-60 rounded"
                            src="{{ getValidImage(path: 'storage/product/thumbnail/' . $wishlist['thumbnail'], type: 'backend-product') }}"
                            alt="{{ $wishlist->name }} image">
                    </div>
                    <div class="fz-12 title-color text-center">
                        {{ isset($wishlist) ? substr($wishlist->name, 0, 30) . (strlen($wishlist->name) > 20 ? '...' : '') : 'not exists' }}
                    </div>
                    <div class="d-flex align-items-center gap-1 fz-10">
                        <span class="rating-color d-flex align-items-center font-weight-bold gap-1">
                            <i class="tio-heart"></i>
                            {{ round($wishlist->wishList->count(), 2) }}
                        </span>
                        {{-- <span class="d-flex align-items-center gap-10">
                            ({{ $wishlist['total'] }} {{ translate('count') }})
                        </span> --}}
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center">
            <p class="text-muted">{{ translate('no_Top_Selling_Products') }}</p>
            <img class="w-75" src="{{ asset('/public/assets/back-end/img/no-data.png') }}" alt="">
        </div>
    @endif
</div>
