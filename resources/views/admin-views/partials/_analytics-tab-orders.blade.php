<div class="row g-3">
    <!-- Sub-filter header for Orders -->
    <div class="col-12">
        <div class="card card-body shadow-sm border-0 d-flex flex-row justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0 d-flex align-items-center gap-2">
                <i class="tio-shopping-cart text-primary fz-20"></i>
                {{ translate('Order Conversion & Lifecycle Funnel') }}
            </h5>
            <div class="d-flex align-items-center gap-3">
                <span class="badge badge-soft-success fz-13 px-3 py-2">
                    <i class="tio-trending-up"></i> {{ translate('Fulfillment Success Rate') }}: <strong>{{ $analytics['order']['delivery_rate'] }}%</strong>
                </span>
                <span class="badge badge-soft-primary fz-13 px-3 py-2">
                    <i class="tio-calculator"></i> {{ translate('Average Order Value (AOV)') }}: <strong>{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $analytics['order']['aov']), currencyCode: getCurrencyCode()) }}</strong>
                </span>
            </div>
        </div>
    </div>

    <!-- Status Grid -->
    <div class="col-sm-6 col-lg-3">
        <a class="card card-body shadow-sm border-0 h-100 text-decoration-none" href="{{ route('admin.orders.list', ['pending']) }}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted text-uppercase fz-12 fw-semibold">{{ translate('Pending Orders') }}</span>
                <span class="badge badge-soft-warning rounded-circle p-2"><i class="tio-time fz-18"></i></span>
            </div>
            <h2 class="fw-bold text-dark mb-1">{{ number_format($analytics['order']['status_counts']['pending']) }}</h2>
            <small class="text-muted">{{ translate('Awaiting confirmation') }}</small>
        </a>
    </div>

    <div class="col-sm-6 col-lg-3">
        <a class="card card-body shadow-sm border-0 h-100 text-decoration-none" href="{{ route('admin.orders.list', ['confirmed']) }}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted text-uppercase fz-12 fw-semibold">{{ translate('Confirmed') }}</span>
                <span class="badge badge-soft-info rounded-circle p-2"><i class="tio-checkmark-circle fz-18"></i></span>
            </div>
            <h2 class="fw-bold text-dark mb-1">{{ number_format($analytics['order']['status_counts']['confirmed']) }}</h2>
            <small class="text-muted">{{ translate('Processing ready') }}</small>
        </a>
    </div>

    <div class="col-sm-6 col-lg-3">
        <a class="card card-body shadow-sm border-0 h-100 text-decoration-none" href="{{ route('admin.orders.list', ['processing']) }}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted text-uppercase fz-12 fw-semibold">{{ translate('Packaging') }}</span>
                <span class="badge badge-soft-primary rounded-circle p-2"><i class="tio-box fz-18"></i></span>
            </div>
            <h2 class="fw-bold text-dark mb-1">{{ number_format($analytics['order']['status_counts']['processing']) }}</h2>
            <small class="text-muted">{{ translate('In preparation') }}</small>
        </a>
    </div>

    <div class="col-sm-6 col-lg-3">
        <a class="card card-body shadow-sm border-0 h-100 text-decoration-none" href="{{ route('admin.orders.list', ['out_for_delivery']) }}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted text-uppercase fz-12 fw-semibold">{{ translate('Out for Delivery') }}</span>
                <span class="badge badge-soft-secondary rounded-circle p-2"><i class="tio-delivery fz-18"></i></span>
            </div>
            <h2 class="fw-bold text-dark mb-1">{{ number_format($analytics['order']['status_counts']['out_for_delivery']) }}</h2>
            <small class="text-muted">{{ translate('Dispatched with agent') }}</small>
        </a>
    </div>

    <div class="col-sm-6 col-lg-3">
        <a class="card card-body shadow-sm border-0 h-100 text-decoration-none border-left-success" href="{{ route('admin.orders.list', ['delivered']) }}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-success text-uppercase fz-12 fw-bold">{{ translate('Delivered') }}</span>
                <span class="badge badge-soft-success rounded-circle p-2"><i class="tio-checkmark-circle-outlined fz-18"></i></span>
            </div>
            <h2 class="fw-bold text-success mb-1">{{ number_format($analytics['order']['status_counts']['delivered']) }}</h2>
            <small class="text-muted">{{ translate('Successfully completed') }}</small>
        </a>
    </div>

    <div class="col-sm-6 col-lg-3">
        <a class="card card-body shadow-sm border-0 h-100 text-decoration-none border-left-danger" href="{{ route('admin.orders.list', ['canceled']) }}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-danger text-uppercase fz-12 fw-bold">{{ translate('Canceled') }}</span>
                <span class="badge badge-soft-danger rounded-circle p-2"><i class="tio-clear-circle fz-18"></i></span>
            </div>
            <h2 class="fw-bold text-danger mb-1">{{ number_format($analytics['order']['status_counts']['canceled']) }}</h2>
            <small class="text-muted">{{ translate('Canceled before dispatch') }}</small>
        </a>
    </div>

    <div class="col-sm-6 col-lg-3">
        <a class="card card-body shadow-sm border-0 h-100 text-decoration-none border-left-warning" href="{{ route('admin.orders.list', ['returned']) }}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-warning text-uppercase fz-12 fw-bold">{{ translate('Returned') }}</span>
                <span class="badge badge-soft-warning rounded-circle p-2"><i class="tio-history fz-18"></i></span>
            </div>
            <h2 class="fw-bold text-warning mb-1">{{ number_format($analytics['order']['status_counts']['returned']) }}</h2>
            <small class="text-muted">{{ translate('Returned by buyer') }}</small>
        </a>
    </div>

    <div class="col-sm-6 col-lg-3">
        <a class="card card-body shadow-sm border-0 h-100 text-decoration-none border-left-dark" href="{{ route('admin.orders.list', ['failed']) }}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-dark text-uppercase fz-12 fw-bold">{{ translate('Failed Delivery') }}</span>
                <span class="badge badge-soft-dark rounded-circle p-2"><i class="tio-error-outlined fz-18"></i></span>
            </div>
            <h2 class="fw-bold text-dark mb-1">{{ number_format($analytics['order']['status_counts']['failed']) }}</h2>
            <small class="text-muted">{{ translate('Failed to deliver') }}</small>
        </a>
    </div>

    <!-- Order Status Funnel Visual Chart -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-0">
                <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                    <i class="tio-chart-bar-1 text-primary"></i>
                    {{ translate('Order Status Visual Volume Breakdown') }}
                </h5>
            </div>
            <div class="card-body">
                <div id="apex-order-status-chart" style="min-height: 250px;"></div>
            </div>
        </div>
    </div>

    <!-- Product Type Breakdown -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-0">
                <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                    <i class="tio-chart-bar text-primary"></i>
                    {{ translate('Order Items Type Split: Physical vs Digital Goods') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded mb-2 d-flex justify-content-between align-items-center">
                            <span class="d-flex align-items-center gap-2"><i class="tio-shop text-primary fz-20"></i> {{ translate('Physical Product Order Items') }}</span>
                            <span class="badge badge-primary fz-14 px-3 py-1">{{ number_format($analytics['order']['product_types']['physical']) }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded mb-2 d-flex justify-content-between align-items-center">
                            <span class="d-flex align-items-center gap-2"><i class="tio-file-text text-info fz-20"></i> {{ translate('Digital Product Order Items') }}</span>
                            <span class="badge badge-info fz-14 px-3 py-1">{{ number_format($analytics['order']['product_types']['digital']) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-success { border-left: 4px solid #28a745 !important; }
.border-left-danger { border-left: 4px solid #dc3545 !important; }
.border-left-warning { border-left: 4px solid #ffc107 !important; }
.border-left-dark { border-left: 4px solid #343a40 !important; }
</style>
