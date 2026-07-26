<div class="row g-3">
    <!-- Vendors Overview Header -->
    <div class="col-12">
        <div class="card card-body shadow-sm border-0 d-flex flex-row justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0 d-flex align-items-center gap-2">
                <i class="tio-shop text-primary fz-20"></i>
                {{ translate('Vendor Ecosystem & Subscription Analytics') }}
            </h5>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.sellers.seller-list') }}" class="btn btn-outline-primary btn-sm rounded-pill">
                    <i class="tio-format-bullets"></i> {{ translate('Manage All Vendors') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Vendor Status Cards -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-body shadow-sm border-0 h-100">
            <span class="text-muted text-uppercase fz-12 fw-semibold mb-1">{{ translate('Total Vendors') }}</span>
            <h2 class="fw-bold mb-1">{{ number_format($analytics['vendor']['total']) }}</h2>
            <div class="d-flex justify-content-between align-items-center fz-12 mt-2">
                <span class="text-success"><i class="tio-checkmark-circle"></i> {{ $analytics['vendor']['approved'] }} {{ translate('Approved') }}</span>
                <span class="text-warning"><i class="tio-time"></i> {{ $analytics['vendor']['pending'] }} {{ translate('Pending') }}</span>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-body shadow-sm border-0 h-100">
            <span class="text-muted text-uppercase fz-12 fw-semibold mb-1">{{ translate('Expiring Subscriptions') }}</span>
            <h2 class="fw-bold text-warning mb-1">{{ number_format($analytics['vendor']['expiring_30_days']) }}</h2>
            <span class="fz-12 text-muted">{{ translate('Expiring in next 30 days') }}</span>
            @if($analytics['vendor']['expiring_7_days'] > 0)
                <span class="badge badge-soft-danger mt-1">{{ $analytics['vendor']['expiring_7_days'] }} {{ translate('expiring within 7 days') }}</span>
            @endif
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-body shadow-sm border-0 h-100">
            <span class="text-muted text-uppercase fz-12 fw-semibold mb-1">{{ translate('Vendor Waiting List') }}</span>
            <h2 class="fw-bold text-info mb-1">{{ number_format($analytics['vendor']['waiting_list_count']) }}</h2>
            <span class="fz-12 text-muted">{{ translate('Vendors awaiting store activation') }}</span>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-body shadow-sm border-0 h-100">
            <span class="text-muted text-uppercase fz-12 fw-semibold mb-1">{{ translate('Denied / Suspended') }}</span>
            <h2 class="fw-bold text-danger mb-1">{{ number_format($analytics['vendor']['suspended']) }}</h2>
            <span class="fz-12 text-muted">{{ translate('Blocked or rejected seller applications') }}</span>
        </div>
    </div>

    <!-- Subscription Plans Distribution -->
    <div class="col-lg-6">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-transparent border-0">
                <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                    <i class="tio-layers text-primary"></i>
                    {{ translate('Subscription Plan Distribution') }}
                </h5>
            </div>
            <div class="card-body">
                <div id="apex-vendor-plans-chart" class="mb-3" style="min-height: 220px;"></div>
                <div class="list-group list-group-flush">
                    @forelse($analytics['vendor']['plan_breakdown'] as $plan)
                        <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0">
                            <span class="fw-semibold text-dark">{{ $plan['name'] }}</span>
                            <span class="badge badge-soft-primary px-3 py-2 font-weight-bold fz-14">
                                {{ number_format($plan['count']) }} {{ translate('Active Vendors') }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">{{ translate('No subscription plans data available') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Top Vendors by Earning -->
    <div class="col-lg-6">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-transparent border-0">
                <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                    <i class="tio-trophy text-warning"></i>
                    {{ translate('Top Earner Vendors') }}
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ translate('Store / Vendor') }}</th>
                                <th class="text-right">{{ translate('Total Earnings') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($analytics['vendor']['top_earning_vendors'] as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="avatar avatar-sm avatar-circle">
                                                <img class="avatar-img" src="{{ asset('storage/app/public/shop/'.($item->seller->shop->image ?? '')) }}" onError="this.src='{{asset('public/assets/back-end/img/160x160/img1.jpg')}}'" alt="">
                                            </span>
                                            <div>
                                                <h6 class="title-color mb-0">{{ $item->seller->shop->name ?? ($item->seller->f_name ?? 'Vendor') }}</h6>
                                                <small class="text-muted">{{ $item->seller->phone ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-right font-weight-bold text-success">
                                        {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $item->total_earning), currencyCode: getCurrencyCode()) }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center text-muted">{{ translate('No vendors data found') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
