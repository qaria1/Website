<div class="row g-3">
    <!-- Header -->
    <div class="col-12">
        <div class="card card-body shadow-sm border-0 d-flex flex-row justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0 d-flex align-items-center gap-2">
                <i class="tio-user-big text-primary fz-20"></i>
                {{ translate('Customer Growth & Retention Analytics') }}
            </h5>
            <a href="{{ route('admin.customer.list') }}" class="btn btn-outline-primary btn-sm rounded-pill">
                <i class="tio-format-bullets"></i> {{ translate('Manage Customers') }}
            </a>
        </div>
    </div>

    <!-- Customer Overview Cards -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-body shadow-sm border-0 h-100">
            <span class="text-muted text-uppercase fz-12 fw-semibold mb-1">{{ translate('Registered Customers') }}</span>
            <h2 class="fw-bold mb-1">{{ number_format($analytics['customer']['total']) }}</h2>
            <div class="d-flex justify-content-between align-items-center fz-12 mt-2">
                <span class="text-success"><i class="tio-checkmark-circle"></i> {{ $analytics['customer']['active'] }} {{ translate('Active') }}</span>
                <span class="text-danger"><i class="tio-block"></i> {{ $analytics['customer']['blocked'] }} {{ translate('Blocked') }}</span>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-body shadow-sm border-0 h-100">
            <span class="text-muted text-uppercase fz-12 fw-semibold mb-1">{{ translate('Average Lifetime Value (CLV)') }}</span>
            <h2 class="fw-bold text-success mb-1">
                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $analytics['customer']['clv_avg']), currencyCode: getCurrencyCode()) }}
            </h2>
            <span class="fz-12 text-muted">{{ translate('Avg spend per registered user') }}</span>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-body shadow-sm border-0 h-100">
            <span class="text-muted text-uppercase fz-12 fw-semibold mb-1">{{ translate('Repeat Buyers') }}</span>
            <h2 class="fw-bold text-primary mb-1">{{ number_format($analytics['customer']['repeat_customers']) }}</h2>
            <span class="fz-12 text-muted">{{ translate('Customers with >1 completed order') }}</span>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-body shadow-sm border-0 h-100">
            <span class="text-muted text-uppercase fz-12 fw-semibold mb-1">{{ translate('One-Time Buyers') }}</span>
            <h2 class="fw-bold text-secondary mb-1">{{ number_format($analytics['customer']['one_time_customers']) }}</h2>
            <span class="fz-12 text-muted">{{ translate('Single purchase customers') }}</span>
        </div>
    </div>

    <!-- Retention & Loyalty Row -->
    <div class="col-lg-6">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-transparent border-0">
                <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                    <i class="tio-chart-pie-1 text-primary"></i>
                    {{ translate('Customer Retention: Repeat vs One-Time Buyers') }}
                </h5>
            </div>
            <div class="card-body">
                <div id="apex-customer-retention-chart" style="min-height: 220px;"></div>
            </div>
        </div>
    </div>

    <!-- Customer Wallets Summary -->
    <div class="col-lg-6">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-transparent border-0">
                <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                    <i class="tio-wallet text-success"></i>
                    {{ translate('Customer Wallet Funds') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="p-4 bg-light rounded text-center">
                    <span class="text-muted fz-13 d-block mb-2">{{ translate('Total Active Customer Wallet Liabilities') }}</span>
                    <h2 class="fw-bold text-success mb-0">
                        {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $analytics['customer']['total_wallet_balance']), currencyCode: getCurrencyCode()) }}
                    </h2>
                    <small class="text-muted mt-2 d-block">{{ translate('Stored funds usable for instant checkout') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
