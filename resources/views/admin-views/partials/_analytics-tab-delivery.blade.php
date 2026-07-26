<div class="row g-3">
    <!-- Header -->
    <div class="col-12">
        <div class="card card-body shadow-sm border-0 d-flex flex-row justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0 d-flex align-items-center gap-2">
                <i class="tio-delivery text-primary fz-20"></i>
                {{ translate('Delivery & Logistics Fleet Analytics') }}
            </h5>
            <a href="{{ route('admin.delivery-man.list') }}" class="btn btn-outline-primary btn-sm rounded-pill">
                <i class="tio-format-bullets"></i> {{ translate('Manage Delivery Men') }}
            </a>
        </div>
    </div>

    <!-- Delivery Overview Cards -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-body shadow-sm border-0 h-100">
            <span class="text-muted text-uppercase fz-12 fw-semibold mb-1">{{ translate('Total Delivery Agents') }}</span>
            <h2 class="fw-bold mb-1">{{ number_format($analytics['delivery']['total']) }}</h2>
            <span class="fz-12 text-success"><i class="tio-checkmark-circle"></i> {{ $analytics['delivery']['active'] }} {{ translate('Active Agents') }}</span>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-body shadow-sm border-0 h-100 border-left-warning">
            <span class="text-warning text-uppercase fz-12 fw-bold mb-1">{{ translate('Uncollected Cash in Hand') }}</span>
            <h2 class="fw-bold text-warning mb-1">
                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $analytics['delivery']['total_cash_in_hand']), currencyCode: getCurrencyCode()) }}
            </h2>
            <span class="fz-12 text-muted">{{ translate('Held by agents from COD orders') }}</span>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-body shadow-sm border-0 h-100">
            <span class="text-muted text-uppercase fz-12 fw-semibold mb-1">{{ translate('Pending Delivery Payouts') }}</span>
            <h2 class="fw-bold text-primary mb-1">
                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $analytics['delivery']['total_pending_withdraw']), currencyCode: getCurrencyCode()) }}
            </h2>
            <span class="fz-12 text-muted">{{ translate('Awaiting admin payout approval') }}</span>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-body shadow-sm border-0 h-100">
            <span class="text-muted text-uppercase fz-12 fw-semibold mb-1">{{ translate('Total Payouts Completed') }}</span>
            <h2 class="fw-bold text-success mb-1">
                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $analytics['delivery']['total_withdrawn']), currencyCode: getCurrencyCode()) }}
            </h2>
            <span class="fz-12 text-muted">{{ translate('Paid out to delivery men') }}</span>
        </div>
    </div>

    <!-- Top Deliverymen Table -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-0">
                <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                    <i class="tio-trophy text-warning"></i>
                    {{ translate('Top Delivery Performers') }}
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ translate('Delivery Man') }}</th>
                                <th>{{ translate('Phone') }}</th>
                                <th class="text-center">{{ translate('Total Orders Delivered') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($analytics['delivery']['top_deliverymen'] as $dm)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="avatar avatar-sm avatar-circle">
                                                <img class="avatar-img" src="{{ asset('storage/app/public/delivery-man/'.$dm->image) }}" onError="this.src='{{asset('public/assets/back-end/img/160x160/img1.jpg')}}'" alt="">
                                            </span>
                                            <div>
                                                <h6 class="title-color mb-0">{{ $dm->f_name }} {{ $dm->l_name }}</h6>
                                                <small class="text-muted">{{ $dm->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $dm->phone }}</td>
                                    <td class="text-center font-weight-bold">
                                        <span class="badge badge-soft-primary px-3 py-2 fz-14">{{ number_format($dm->orders_count) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted">{{ translate('No deliverymen data available') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
