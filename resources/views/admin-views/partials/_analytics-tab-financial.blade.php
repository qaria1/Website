<div class="row g-3">
    <!-- Sub-Filter Controls for Financial -->
    <div class="col-12">
        <div class="card card-body shadow-sm border-0 d-flex flex-row justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0 d-flex align-items-center gap-2">
                <i class="tio-dollar-outlined text-primary fz-20"></i>
                {{ translate('Financial Performance Overview') }}
            </h5>
            <div class="d-flex align-items-center gap-2">
                <select class="custom-select custom-select-sm w-auto" id="financial_groupby">
                    <option value="month" selected>{{ translate('Monthly View') }}</option>
                    <option value="day">{{ translate('Daily Breakdown') }}</option>
                </select>
                <button class="btn btn-primary btn-sm rounded-pill" onclick="refreshFinancialTab()">
                    <i class="tio-refresh"></i> {{ translate('Refresh Financial Data') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Financial Cards Row -->
    <div class="col-sm-6 col-lg-3">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted text-capitalize fz-13">{{ translate('In-House Earning') }}</span>
                    <span class="badge badge-soft-primary rounded-pill"><i class="tio-shop"></i></span>
                </div>
                <h3 class="fw-bold mb-1" id="fin-inhouse-earning">
                    {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $analytics['financial']['wallet_overview']['admin_inhouse']), currencyCode: getCurrencyCode()) }}
                </h3>
                <span class="fz-12 text-muted">{{ translate('Direct In-house product revenue') }}</span>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted text-capitalize fz-13">{{ translate('Commission Earned') }}</span>
                    <span class="badge badge-soft-success rounded-pill"><i class="tio-percentage"></i></span>
                </div>
                <h3 class="fw-bold mb-1" id="fin-commission-earned">
                    {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $analytics['financial']['wallet_overview']['admin_commission']), currencyCode: getCurrencyCode()) }}
                </h3>
                <span class="fz-12 text-muted">{{ translate('Vendor order sales commissions') }}</span>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted text-capitalize fz-13">{{ translate('Delivery Charges Earned') }}</span>
                    <span class="badge badge-soft-info rounded-pill"><i class="tio-delivery"></i></span>
                </div>
                <h3 class="fw-bold mb-1" id="fin-delivery-charge">
                    {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $analytics['financial']['wallet_overview']['admin_delivery_charge']), currencyCode: getCurrencyCode()) }}
                </h3>
                <span class="fz-12 text-muted">{{ translate('Total shipping charge revenue') }}</span>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted text-capitalize fz-13">{{ translate('Pending Admin Balance') }}</span>
                    <span class="badge badge-soft-warning rounded-pill"><i class="tio-time"></i></span>
                </div>
                <h3 class="fw-bold mb-1" id="fin-pending-amount">
                    {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $analytics['financial']['wallet_overview']['admin_pending']), currencyCode: getCurrencyCode()) }}
                </h3>
                <span class="fz-12 text-muted">{{ translate('Unsettled order amounts') }}</span>
            </div>
        </div>
    </div>

    <!-- Payment Methods Split & Donut Chart -->
    <div class="col-lg-6">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-transparent border-0 pb-0">
                <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                    <i class="tio-credit-card text-success"></i>
                    {{ translate('Payment Methods Distribution') }}
                </h5>
            </div>
            <div class="card-body">
                <div id="apex-payment-method-chart" class="mb-3" style="min-height: 200px;"></div>
                <div class="table-responsive">
                    <table class="table table-align-middle table-borderless table-sm mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ translate('Payment Method') }}</th>
                                <th>{{ translate('Orders Count') }}</th>
                                <th class="text-right">{{ translate('Total Volume') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <span class="d-flex align-items-center gap-2">
                                        <i class="tio-money text-success"></i> {{ translate('Cash / Pay on Delivery') }}
                                    </span>
                                </td>
                                <td><span class="badge badge-soft-dark">{{ number_format($analytics['financial']['payment_counts']['cash']) }}</span></td>
                                <td class="text-right font-weight-bold">{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $analytics['financial']['payment_methods']['cash']), currencyCode: getCurrencyCode()) }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="d-flex align-items-center gap-2">
                                        <i class="tio-credit-cards text-primary"></i> {{ translate('Digital / Online Payment') }}
                                    </span>
                                </td>
                                <td><span class="badge badge-soft-dark">{{ number_format($analytics['financial']['payment_counts']['digital']) }}</span></td>
                                <td class="text-right font-weight-bold">{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $analytics['financial']['payment_methods']['digital']), currencyCode: getCurrencyCode()) }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="d-flex align-items-center gap-2">
                                        <i class="tio-wallet text-info"></i> {{ translate('Customer Wallet') }}
                                    </span>
                                </td>
                                <td><span class="badge badge-soft-dark">{{ number_format($analytics['financial']['payment_counts']['wallet']) }}</span></td>
                                <td class="text-right font-weight-bold">{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $analytics['financial']['payment_methods']['wallet']), currencyCode: getCurrencyCode()) }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="d-flex align-items-center gap-2">
                                        <i class="tio-receipt text-warning"></i> {{ translate('Offline Payment') }}
                                    </span>
                                </td>
                                <td><span class="badge badge-soft-dark">{{ number_format($analytics['financial']['payment_counts']['offline']) }}</span></td>
                                <td class="text-right font-weight-bold">{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $analytics['financial']['payment_methods']['offline']), currencyCode: getCurrencyCode()) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- All Wallet Balances Summary -->
    <div class="col-lg-6">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-transparent border-0 pb-0">
                <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                    <i class="tio-account-square-wallet text-info"></i>
                    {{ translate('Ecosystem Wallet Balances') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-12">
                        <div class="p-3 bg-light rounded d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 text-muted">{{ translate('Total Vendor Wallets Net Balance') }}</h6>
                                <small class="text-muted">{{ translate('Earned minus withdrawn') }}</small>
                            </div>
                            <h4 class="mb-0 text-primary fw-bold">
                                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $analytics['financial']['wallet_overview']['total_seller_wallet_balance']), currencyCode: getCurrencyCode()) }}
                            </h4>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="p-3 bg-light rounded d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 text-muted">{{ translate('Total Deliveryman Wallets Balance') }}</h6>
                                <small class="text-muted">{{ translate('Current delivery earnings') }}</small>
                            </div>
                            <h4 class="mb-0 text-info fw-bold">
                                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $analytics['financial']['wallet_overview']['total_deliveryman_wallet_balance']), currencyCode: getCurrencyCode()) }}
                            </h4>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="p-3 bg-light rounded d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 text-muted">{{ translate('Total Customer Wallets Balance') }}</h6>
                                <small class="text-muted">{{ translate('Customer stored funds') }}</small>
                            </div>
                            <h4 class="mb-0 text-success fw-bold">
                                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $analytics['financial']['wallet_overview']['total_customer_wallet_balance']), currencyCode: getCurrencyCode()) }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
