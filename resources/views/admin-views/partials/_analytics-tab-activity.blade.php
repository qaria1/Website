<div class="row g-3">
    <!-- Header -->
    <div class="col-12">
        <div class="card card-body shadow-sm border-0 d-flex flex-row justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0 d-flex align-items-center gap-2">
                <i class="tio-bolt text-warning fz-20"></i>
                {{ translate('System Action Alerts & Live Stream') }}
            </h5>
        </div>
    </div>

    <!-- Action Alerts Summary Grid -->
    <div class="col-lg-5">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-transparent border-0">
                <h5 class="card-title mb-0 text-danger d-flex align-items-center gap-2">
                    <i class="tio-notice text-danger"></i>
                    {{ translate('Action Required Items') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.sellers.seller-list') }}" class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 text-decoration-none">
                        <span class="text-dark"><i class="tio-shop text-warning mr-2"></i> {{ translate('Pending Vendor Applications') }}</span>
                        <span class="badge badge-soft-warning px-3 py-1 font-weight-bold fz-14">{{ $analytics['activity_alerts']['alerts']['pending_vendors'] }}</span>
                    </a>

                    <a href="{{ route('admin.sellers.withdraw_list') }}" class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 text-decoration-none">
                        <span class="text-dark"><i class="tio-wallet text-danger mr-2"></i> {{ translate('Pending Vendor Payout Requests') }}</span>
                        <span class="badge badge-soft-danger px-3 py-1 font-weight-bold fz-14">{{ $analytics['activity_alerts']['alerts']['pending_seller_withdraws'] }}</span>
                    </a>

                    <a href="{{ route('admin.delivery-man.withdraw-list') }}" class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 text-decoration-none">
                        <span class="text-dark"><i class="tio-delivery text-info mr-2"></i> {{ translate('Pending Delivery Payout Requests') }}</span>
                        <span class="badge badge-soft-info px-3 py-1 font-weight-bold fz-14">{{ $analytics['activity_alerts']['alerts']['pending_delivery_withdraws'] }}</span>
                    </a>

                    <a href="{{ route('admin.refund-section.refund.list', ['pending']) }}" class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 text-decoration-none">
                        <span class="text-dark"><i class="tio-history text-warning mr-2"></i> {{ translate('Pending Refund Requests') }}</span>
                        <span class="badge badge-soft-warning px-3 py-1 font-weight-bold fz-14">{{ $analytics['activity_alerts']['alerts']['pending_refunds'] }}</span>
                    </a>

                    <a href="{{ route('admin.products.list', ['in_house']) }}" class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 text-decoration-none">
                        <span class="text-dark"><i class="tio-error-outlined text-danger mr-2"></i> {{ translate('Low / Out of Stock Items') }}</span>
                        <span class="badge badge-soft-danger px-3 py-1 font-weight-bold fz-14">{{ $analytics['activity_alerts']['alerts']['low_stock_products'] }}</span>
                    </a>

                    <a href="{{ route('admin.business-settings.subscription.index') }}" class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 text-decoration-none">
                        <span class="text-dark"><i class="tio-time text-warning mr-2"></i> {{ translate('Expiring Vendor Subscriptions (< 7d)') }}</span>
                        <span class="badge badge-soft-warning px-3 py-1 font-weight-bold fz-14">{{ $analytics['activity_alerts']['alerts']['expiring_subscriptions'] }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Stream -->
    <div class="col-lg-7">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                    <i class="tio-shopping-cart text-primary"></i>
                    {{ translate('Latest Orders Activity Stream') }}
                </h5>
                <a href="{{ route('admin.orders.list', ['all']) }}" class="fz-12 text-primary">{{ translate('View All Orders') }} &rarr;</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ translate('Order ID') }}</th>
                                <th>{{ translate('Customer') }}</th>
                                <th>{{ translate('Amount') }}</th>
                                <th>{{ translate('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($analytics['activity_alerts']['latest_orders'] as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.details', ['id' => $order->id]) }}" class="font-weight-bold text-primary">
                                            #{{ $order->id }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($order->is_guest)
                                            <span class="badge badge-soft-secondary">{{ translate('Guest User') }}</span>
                                        @else
                                            <span class="text-dark fw-semibold">{{ $order->customer->f_name ?? '' }} {{ $order->customer->l_name ?? '' }}</span>
                                        @endif
                                    </td>
                                    <td class="font-weight-bold">
                                        {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order->order_amount), currencyCode: getCurrencyCode()) }}
                                    </td>
                                    <td>
                                        <span class="badge badge-soft-{{ $order->order_status == 'delivered' ? 'success' : ($order->order_status == 'pending' ? 'warning' : 'info') }} text-capitalize">
                                            {{ translate($order->order_status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">{{ translate('No recent orders') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
