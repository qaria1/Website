@extends('layouts.back-end.app')
@section('title', translate('dashboard'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .analytics-tab-nav .nav-link {
            font-weight: 600;
            color: #4b5563;
            border-radius: 8px;
            padding: 10px 18px;
            transition: all 0.2s ease-in-out;
            border: 1px solid transparent;
            margin-right: 4px;
            margin-bottom: 6px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .analytics-tab-nav .nav-link:hover {
            background-color: #f3f4f6;
            color: #1f2937;
        }
        .analytics-tab-nav .nav-link.active {
            background-color: #3b82f6;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
        }
        .global-filter-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid #e2e8f0;
            border-radius: 12px;
        }
    </style>
@endpush

@section('content')
    @if(auth('admin')->user()->admin_role_id==1 || \App\Utils\Helpers::module_permission_check('dashboard'))
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header pb-2 mb-3 border-0">
                <div class="flex-between align-items-center">
                    <div>
                        <h1 class="page-header-title d-flex align-items-center gap-2">
                            <i class="tio-chart-pie-1 text-primary"></i>
                            {{ translate('Executive Analytics Command Center') }}
                        </h1>
                        <p class="text-muted mb-0">{{ translate('Comprehensive real-time platform data & business decision insights') }}.</p>
                    </div>
                </div>
            </div>

            <!-- Global Data Filter Bar -->
            <div class="card global-filter-card mb-4 shadow-sm">
                <div class="card-body p-3">
                    <form id="global-analytics-filter-form" class="row align-items-center g-2">
                        <div class="col-md-3">
                            <label class="form-label fz-12 font-weight-bold text-uppercase text-muted mb-1">{{ translate('From Date') }}</label>
                            <input type="date" class="form-control form-control-sm" id="filter_from_date" name="from">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fz-12 font-weight-bold text-uppercase text-muted mb-1">{{ translate('To Date') }}</label>
                            <input type="date" class="form-control form-control-sm" id="filter_to_date" name="to">
                        </div>
                        <div class="col-md-4 d-flex align-items-end gap-1 mt-auto">
                            <button type="button" class="btn btn-outline-secondary btn-sm px-2" onclick="setPresetDate('today')">{{ translate('Today') }}</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm px-2" onclick="setPresetDate('this_week')">{{ translate('This Week') }}</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm px-2" onclick="setPresetDate('this_month')">{{ translate('This Month') }}</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm px-2" onclick="setPresetDate('this_year')">{{ translate('This Year') }}</button>
                        </div>
                        <div class="col-md-2 d-flex align-items-end justify-content-end gap-2 mt-auto">
                            <button type="submit" class="btn btn-primary btn-sm px-3 rounded-pill">
                                <i class="tio-filter"></i> {{ translate('Apply Filter') }}
                            </button>
                            <button type="button" class="btn btn-light btn-sm px-2 rounded-pill" onclick="resetGlobalFilter()">
                                <i class="tio-clear"></i> {{ translate('Reset') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Hero KPI Bar (Always Visible Above Tabs) -->
            @include('admin-views.partials._analytics-hero-kpi')

            <!-- Priority Category Nav Tabs -->
            <ul class="nav nav-pills analytics-tab-nav mb-4" id="analyticsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="financial-tab" data-toggle="pill" href="#tab-financial" role="tab" aria-controls="tab-financial" aria-selected="true">
                        <i class="tio-dollar"></i> 1. {{ translate('Financial & Earnings') }}
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="orders-tab" data-toggle="pill" href="#tab-orders" role="tab" aria-controls="tab-orders" aria-selected="false">
                        <i class="tio-shopping-cart"></i> 2. {{ translate('Orders & Funnel') }}
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="vendors-tab" data-toggle="pill" href="#tab-vendors" role="tab" aria-controls="tab-vendors" aria-selected="false">
                        <i class="tio-shop"></i> 3. {{ translate('Vendors & Subscriptions') }}
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="customers-tab" data-toggle="pill" href="#tab-customers" role="tab" aria-controls="tab-customers" aria-selected="false">
                        <i class="tio-user"></i> 4. {{ translate('Customers & Retention') }}
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="products-tab" data-toggle="pill" href="#tab-products" role="tab" aria-controls="tab-products" aria-selected="false">
                        <i class="tio-label"></i> 5. {{ translate('Products & Catalog') }}
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="delivery-tab" data-toggle="pill" href="#tab-delivery" role="tab" aria-controls="tab-delivery" aria-selected="false">
                        <i class="tio-delivery"></i> 6. {{ translate('Delivery & Fleet') }}
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="refunds-tab" data-toggle="pill" href="#tab-refunds" role="tab" aria-controls="tab-refunds" aria-selected="false">
                        <i class="tio-history"></i> 7. {{ translate('Refunds & Support') }}
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="activity-tab" data-toggle="pill" href="#tab-activity" role="tab" aria-controls="tab-activity" aria-selected="false">
                        <i class="tio-bolt"></i> 8. {{ translate('Activity & Alerts') }}
                    </a>
                </li>
            </ul>

            <!-- Tab Content Panes -->
            <div class="tab-content" id="analyticsTabsContent">

                <!-- 1. Financial Tab -->
                <div class="tab-pane fade show active" id="tab-financial" role="tabpanel" aria-labelledby="financial-tab">
                    @include('admin-views.partials._analytics-tab-financial')

                    <!-- Embedded Original Earnings Graph -->
                    <div class="card shadow-sm border-0 mt-4">
                        <div class="card-body">
                            <div class="row g-2 align-items-center mb-3">
                                <div class="col-md-6">
                                    <h5 class="d-flex align-items-center gap-2 mb-0">
                                        <img src="{{asset('/public/assets/back-end/img/earning_statictics.png')}}" alt="">
                                        {{translate('Earnings Statistics Trend')}}
                                    </h5>
                                </div>
                                <div class="col-md-6 d-flex justify-content-md-end">
                                    <ul class="option-select-btn">
                                        <li>
                                            <label class="basic-box-shadow">
                                                <input type="radio" name="statistics2" hidden="" checked="">
                                                <span data-earn-type="yearEarn" class="earning-statistics">{{translate('this_Year')}}</span>
                                            </label>
                                        </li>
                                        <li>
                                            <label class="basic-box-shadow">
                                                <input type="radio" name="statistics2" hidden="">
                                                <span data-earn-type="MonthEarn" class="earning-statistics">{{translate('this_Month')}}</span>
                                            </label>
                                        </li>
                                        <li>
                                            <label class="basic-box-shadow">
                                                <input type="radio" name="statistics2" hidden="">
                                                <span data-earn-type="WeekEarn" class="earning-statistics">{{translate('this_Week')}}</span>
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="mt-2" id="set-new-graph">
                                <div id="apex-main-earnings-trend" style="min-height: 320px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Orders Tab -->
                <div class="tab-pane fade" id="tab-orders" role="tabpanel" aria-labelledby="orders-tab">
                    @include('admin-views.partials._analytics-tab-orders')
                </div>

                <!-- 3. Vendors Tab -->
                <div class="tab-pane fade" id="tab-vendors" role="tabpanel" aria-labelledby="vendors-tab">
                    @include('admin-views.partials._analytics-tab-vendors')

                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <div class="card h-100 shadow-sm border-0">
                                @include('admin-views.partials._top-store-by-order',['top_store_by_order_received'=>$data['top_store_by_order_received']])
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 shadow-sm border-0">
                                @include('admin-views.partials._top-selling-store',['topVendorByEarning'=>$data['topVendorByEarning']])
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Customers Tab -->
                <div class="tab-pane fade" id="tab-customers" role="tabpanel" aria-labelledby="customers-tab">
                    @include('admin-views.partials._analytics-tab-customers')

                    <div class="row g-3 mt-3">
                        <div class="col-12">
                            <div class="card shadow-sm border-0">
                                @include('admin-views.partials._top-customer',['top_customer'=>$data['top_customer']])
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5. Products Tab -->
                <div class="tab-pane fade" id="tab-products" role="tabpanel" aria-labelledby="products-tab">
                    @include('admin-views.partials._analytics-tab-products')

                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <div class="card h-100 shadow-sm border-0">
                                @include('admin-views.partials._most-rated-products',['mostRatedProducts'=>$data['mostRatedProducts']])
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 shadow-sm border-0">
                                @include('admin-views.partials._top-selling-products',['topSellProduct'=>$data['topSellProduct']])
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 6. Delivery Tab -->
                <div class="tab-pane fade" id="tab-delivery" role="tabpanel" aria-labelledby="delivery-tab">
                    @include('admin-views.partials._analytics-tab-delivery')
                </div>

                <!-- 7. Refunds & Support Tab -->
                <div class="tab-pane fade" id="tab-refunds" role="tabpanel" aria-labelledby="refunds-tab">
                    @include('admin-views.partials._analytics-tab-refunds')
                </div>

                <!-- 8. Activity & Alerts Tab -->
                <div class="tab-pane fade" id="tab-activity" role="tabpanel" aria-labelledby="activity-tab">
                    @include('admin-views.partials._analytics-tab-activity')
                </div>

            </div>
        </div>
    @else
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-12 mb-2 mb-sm-0">
                        <h3 class="text-center">{{translate('hi')}} {{auth('admin')->user()->name}}, {{translate('welcome_to_dashboard')}}.</h3>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <span id="analytics-data-url" data-url="{{ route('admin.dashboard.analytics-data') }}"></span>
    <span id="earning-statistics-url" data-url="{{ route('admin.dashboard.earning-statistics') }}"></span>
    <span id="order-status-url" data-url="{{ route('admin.dashboard.order-status') }}"></span>
@endsection

@push('script')
    <script src="{{asset('public/assets/back-end/vendor/chart.js/dist/Chart.min.js')}}"></script>
    <script src="{{asset('public/assets/back-end/vendor/chart.js.extensions/chartjs-extensions.js')}}"></script>
    <script src="{{asset('public/assets/back-end/vendor/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endpush

@push('script_2')
    <script src="{{asset('public/assets/back-end/js/admin/dashboard.js')}}"></script>
    <script>
        let mainEarningsChart, paymentChart, orderStatusChart, vendorPlansChart, customerRetentionChart, productRatingsChart;

        $(document).ready(function() {
            initApexCharts();
        });

        function initApexCharts() {
            // 0. Main Earnings Statistics Trend (Smooth Area Chart with Gradients)
            const mainEarningsOptions = {
                series: [
                    {
                        name: 'In-House Sales',
                        data: [{{ implode(',', array_values($inhouseEarningStatisticsData)) }}]
                    },
                    {
                        name: 'Vendor Sales',
                        data: [{{ implode(',', array_values($sellerEarningStatisticsData)) }}]
                    },
                    {
                        name: 'Admin Commission',
                        data: [{{ implode(',', array_values($commissionEarningStatisticsData)) }}]
                    },
                    {
                        name: 'Subscription Revenue',
                        data: [{{ implode(',', array_values($subscriptionEarningStatisticsData)) }}]
                    }
                ],
                chart: {
                    type: 'area',
                    height: 320,
                    toolbar: { show: false },
                    fontFamily: 'Inter, sans-serif'
                },
                colors: ['#10B981', '#3B82F6', '#F59E0B', '#8B5CF6'],
                stroke: { curve: 'smooth', width: 2.5 },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.05,
                        stops: [0, 90, 100]
                    }
                },
                xaxis: {
                    categories: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        formatter: function (val) {
                            return val.toLocaleString() + ' ETB';
                        }
                    }
                },
                grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                legend: { position: 'top', horizontalAlign: 'right' },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val.toLocaleString() + ' ETB';
                        }
                    }
                }
            };
            if ($('#apex-main-earnings-trend').length) {
                mainEarningsChart = new ApexCharts(document.querySelector("#apex-main-earnings-trend"), mainEarningsOptions);
                mainEarningsChart.render();
            }

            // 1. Payment Method Donut Chart with Center Total
            const paymentOptions = {
                series: [
                    {{ $analytics['financial']['payment_methods']['cash'] }},
                    {{ $analytics['financial']['payment_methods']['digital'] }},
                    {{ $analytics['financial']['payment_methods']['wallet'] }},
                    {{ $analytics['financial']['payment_methods']['offline'] }}
                ],
                labels: ['Cash / COD', 'Digital Payment', 'Customer Wallet', 'Offline Payment'],
                chart: { type: 'donut', height: 260, fontFamily: 'Inter, sans-serif' },
                colors: ['#10B981', '#3B82F6', '#06B6D4', '#F59E0B'],
                legend: { position: 'bottom' },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '72%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'PAYMENTS',
                                    formatter: function (w) {
                                        const sum = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                        return sum.toLocaleString() + ' ETB';
                                    }
                                }
                            }
                        }
                    }
                },
                dataLabels: { enabled: false }
            };
            if ($('#apex-payment-method-chart').length) {
                paymentChart = new ApexCharts(document.querySelector("#apex-payment-method-chart"), paymentOptions);
                paymentChart.render();
            }

            // 2. Order Status Volume Column Chart with Rounded Tops
            const orderStatusOptions = {
                series: [{
                    name: 'Orders Count',
                    data: [
                        {{ $analytics['order']['status_counts']['pending'] }},
                        {{ $analytics['order']['status_counts']['confirmed'] }},
                        {{ $analytics['order']['status_counts']['processing'] }},
                        {{ $analytics['order']['status_counts']['out_for_delivery'] }},
                        {{ $analytics['order']['status_counts']['delivered'] }},
                        {{ $analytics['order']['status_counts']['canceled'] }},
                        {{ $analytics['order']['status_counts']['returned'] }},
                        {{ $analytics['order']['status_counts']['failed'] }}
                    ]
                }],
                chart: { type: 'bar', height: 260, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                colors: ['#F59E0B', '#06B6D4', '#3B82F6', '#64748B', '#10B981', '#EF4444', '#F97316', '#1E293B'],
                plotOptions: {
                    bar: {
                        distributed: true,
                        borderRadius: 6,
                        columnWidth: '45%'
                    }
                },
                xaxis: {
                    categories: ['Pending', 'Confirmed', 'Packaging', 'Out Delivery', 'Delivered', 'Canceled', 'Returned', 'Failed']
                },
                grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                legend: { show: false }
            };
            if ($('#apex-order-status-chart').length) {
                orderStatusChart = new ApexCharts(document.querySelector("#apex-order-status-chart"), orderStatusOptions);
                orderStatusChart.render();
            }

            // 3. Vendor Plans Donut Chart
            @php
                $planNames = array_column($analytics['vendor']['plan_breakdown'], 'name');
                $planCounts = array_column($analytics['vendor']['plan_breakdown'], 'count');
            @endphp
            const vendorPlanOptions = {
                series: {!! json_encode($planCounts) !!},
                labels: {!! json_encode($planNames) !!},
                chart: { type: 'donut', height: 260, fontFamily: 'Inter, sans-serif' },
                colors: ['#3B82F6', '#10B981', '#F59E0B', '#8B5CF6', '#EF4444', '#64748B'],
                legend: { position: 'bottom' },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '72%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'VENDORS',
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    }
                                }
                            }
                        }
                    }
                },
                dataLabels: { enabled: false }
            };
            if ($('#apex-vendor-plans-chart').length) {
                vendorPlansChart = new ApexCharts(document.querySelector("#apex-vendor-plans-chart"), vendorPlanOptions);
                vendorPlansChart.render();
            }

            // 4. Customer Retention Donut Chart
            const customerRetentionOptions = {
                series: [
                    {{ $analytics['customer']['repeat_customers'] }},
                    {{ $analytics['customer']['one_time_customers'] }}
                ],
                labels: ['Repeat Buyers (>1 Order)', 'One-Time Buyers'],
                chart: { type: 'donut', height: 260, fontFamily: 'Inter, sans-serif' },
                colors: ['#3B82F6', '#94A3B8'],
                legend: { position: 'bottom' },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '72%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'BUYERS',
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    }
                                }
                            }
                        }
                    }
                },
                dataLabels: { enabled: false }
            };
            if ($('#apex-customer-retention-chart').length) {
                customerRetentionChart = new ApexCharts(document.querySelector("#apex-customer-retention-chart"), customerRetentionOptions);
                customerRetentionChart.render();
            }

            // 5. Product Ratings Column Chart
            const productRatingOptions = {
                series: [{
                    name: 'Reviews Count',
                    data: [
                        {{ $analytics['product']['rating_distribution'][5] ?? 0 }},
                        {{ $analytics['product']['rating_distribution'][4] ?? 0 }},
                        {{ $analytics['product']['rating_distribution'][3] ?? 0 }},
                        {{ $analytics['product']['rating_distribution'][2] ?? 0 }},
                        {{ $analytics['product']['rating_distribution'][1] ?? 0 }}
                    ]
                }],
                chart: { type: 'bar', height: 240, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                colors: ['#F59E0B'],
                plotOptions: {
                    bar: {
                        borderRadius: 6,
                        columnWidth: '35%'
                    }
                },
                xaxis: { categories: ['5 Stars', '4 Stars', '3 Stars', '2 Stars', '1 Star'] },
                grid: { borderColor: '#f1f5f9', strokeDashArray: 4 }
            };
            if ($('#apex-product-ratings-chart').length) {
                productRatingsChart = new ApexCharts(document.querySelector("#apex-product-ratings-chart"), productRatingOptions);
                productRatingsChart.render();
            }
        }

        function setPresetDate(preset) {
            const today = new Date();
            let fromDate, toDate;
            toDate = today.toISOString().split('T')[0];

            if (preset === 'today') {
                fromDate = toDate;
            } else if (preset === 'this_week') {
                const first = today.getDate() - today.getDay();
                fromDate = new Date(today.setDate(first)).toISOString().split('T')[0];
            } else if (preset === 'this_month') {
                fromDate = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
            } else if (preset === 'this_year') {
                fromDate = new Date(today.getFullYear(), 0, 1).toISOString().split('T')[0];
            }

            $('#filter_from_date').val(fromDate);
            $('#filter_to_date').val(toDate);
            fetchAnalyticsData(fromDate, toDate);
        }

        function resetGlobalFilter() {
            $('#filter_from_date').val('');
            $('#filter_to_date').val('');
            fetchAnalyticsData('', '');
        }

        $('#global-analytics-filter-form').on('submit', function(e) {
            e.preventDefault();
            const from = $('#filter_from_date').val();
            const to = $('#filter_to_date').val();
            fetchAnalyticsData(from, to);
        });

        function refreshFinancialTab() {
            const from = $('#filter_from_date').val();
            const to = $('#filter_to_date').val();
            const groupBy = $('#financial_groupby').val();
            fetchAnalyticsData(from, to, groupBy);
        }

        function fetchAnalyticsData(from, to, groupBy = 'month') {
            const url = $('#analytics-data-url').data('url');
            $.ajax({
                url: url,
                type: 'GET',
                data: { from: from, to: to, group_by: groupBy },
                beforeSend: function() {
                    $('#hero-kpi-bar').css('opacity', '0.5');
                },
                success: function(response) {
                    $('#hero-kpi-bar').css('opacity', '1');
                    if (response.global) {
                        $('#kpi-gross-revenue').text(response.global.total_gross_revenue.toLocaleString() + ' ETB');
                        $('#kpi-net-admin-earning').text(response.global.net_admin_earning.toLocaleString() + ' ETB');
                        $('#kpi-total-orders').text(response.global.total_orders);
                        $('#kpi-pending-withdrawals').text(response.global.pending_withdrawals.toLocaleString() + ' ETB');
                    }
                    if (response.financial && paymentChart) {
                        paymentChart.updateSeries([
                            response.financial.payment_methods.cash,
                            response.financial.payment_methods.digital,
                            response.financial.payment_methods.wallet,
                            response.financial.payment_methods.offline
                        ]);
                    }
                    if (response.financial && mainEarningsChart && response.financial.timeline) {
                        mainEarningsChart.updateOptions({
                            xaxis: { categories: response.financial.timeline.labels }
                        });
                        mainEarningsChart.updateSeries([
                            { name: 'In-House Sales', data: response.financial.timeline.inhouse },
                            { name: 'Vendor Sales', data: response.financial.timeline.seller },
                            { name: 'Admin Commission', data: response.financial.timeline.commission },
                            { name: 'Subscription Revenue', data: response.financial.timeline.subscription }
                        ]);
                    }
                    if (response.order && orderStatusChart) {
                        orderStatusChart.updateSeries([{
                            data: [
                                response.order.status_counts.pending,
                                response.order.status_counts.confirmed,
                                response.order.status_counts.processing,
                                response.order.status_counts.out_for_delivery,
                                response.order.status_counts.delivered,
                                response.order.status_counts.canceled,
                                response.order.status_counts.returned,
                                response.order.status_counts.failed
                            ]
                        }]);
                    }
                },
                error: function() {
                    $('#hero-kpi-bar').css('opacity', '1');
                }
            });
        }
    </script>
@endpush
