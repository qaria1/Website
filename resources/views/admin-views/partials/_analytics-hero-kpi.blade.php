<div class="row g-2 mb-4" id="hero-kpi-bar">
    <div class="col-sm-6 col-lg-3">
        <div class="card card-body h-100 shadow-sm border-0 bg-gradient-primary text-white p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-uppercase fz-12 fw-semibold opacity-75">{{ translate('Total Gross Revenue') }}</span>
                <i class="tio-money-vs fz-24"></i>
            </div>
            <h2 class="mb-1 text-white fw-bold" id="kpi-gross-revenue">
                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $analytics['global']['total_gross_revenue']), currencyCode: getCurrencyCode()) }}
            </h2>
            <div class="fz-12 opacity-75 d-flex align-items-center gap-1">
                <i class="tio-trending-up text-success-light"></i>
                <span>{{ translate('Delivered Orders GMV') }}</span>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-body h-100 shadow-sm border-0 bg-gradient-success text-white p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-uppercase fz-12 fw-semibold opacity-75">{{ translate('Net Admin Earning') }}</span>
                <i class="tio-wallet fz-24"></i>
            </div>
            <h2 class="mb-1 text-white fw-bold" id="kpi-net-admin-earning">
                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $analytics['global']['net_admin_earning']), currencyCode: getCurrencyCode()) }}
            </h2>
            <div class="fz-12 opacity-75 d-flex align-items-center gap-1">
                <i class="tio-checkmark-circle-outlined"></i>
                <span>{{ translate('In-house + Commission') }}</span>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-body h-100 shadow-sm border-0 bg-gradient-info text-white p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-uppercase fz-12 fw-semibold opacity-75">{{ translate('Total Orders') }}</span>
                <i class="tio-shopping-cart-filled fz-24"></i>
            </div>
            <h2 class="mb-1 text-white fw-bold" id="kpi-total-orders">
                {{ number_format($analytics['global']['total_orders']) }}
            </h2>
            <div class="fz-12 opacity-75 d-flex align-items-center gap-1">
                <i class="tio-shopping-basket font-weight-bold"></i>
                <span>{{ translate('Lifetime Orders Placed') }}</span>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-body h-100 shadow-sm border-0 bg-gradient-warning text-white p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-uppercase fz-12 fw-semibold opacity-75">{{ translate('Pending Withdrawals') }}</span>
                <i class="tio-time fz-24"></i>
            </div>
            <h2 class="mb-1 text-white fw-bold" id="kpi-pending-withdrawals">
                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $analytics['global']['pending_withdrawals']), currencyCode: getCurrencyCode()) }}
            </h2>
            <div class="fz-12 opacity-75 d-flex align-items-center gap-1">
                <i class="tio-notice"></i>
                <span>{{ translate('Seller & Deliveryman Payouts') }}</span>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); }
.bg-gradient-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
.bg-gradient-info { background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%); }
.bg-gradient-warning { background: linear-gradient(135deg, #ff9966 0%, #ff5e62 100%); }
.text-success-light { color: #80ffdb; }
</style>
