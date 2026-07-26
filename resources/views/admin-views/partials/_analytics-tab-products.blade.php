<div class="row g-3">
    <!-- Header -->
    <div class="col-12">
        <div class="card card-body shadow-sm border-0 d-flex flex-row justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0 d-flex align-items-center gap-2">
                <i class="tio-label text-primary fz-20"></i>
                {{ translate('Product Catalog & Inventory Health') }}
            </h5>
            <a href="{{ route('admin.products.list', ['in_house']) }}" class="btn btn-outline-primary btn-sm rounded-pill">
                <i class="tio-format-bullets"></i> {{ translate('Manage Products') }}
            </a>
        </div>
    </div>

    <!-- Product Stats Cards -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-body shadow-sm border-0 h-100">
            <span class="text-muted text-uppercase fz-12 fw-semibold mb-1">{{ translate('Total Products') }}</span>
            <h2 class="fw-bold mb-1">{{ number_format($analytics['product']['total']) }}</h2>
            <div class="d-flex justify-content-between align-items-center fz-12 mt-2">
                <span class="text-success"><i class="tio-checkmark-circle"></i> {{ $analytics['product']['active'] }} {{ translate('Active') }}</span>
                <span class="text-warning"><i class="tio-time"></i> {{ $analytics['product']['pending'] }} {{ translate('Pending') }}</span>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-body shadow-sm border-0 h-100 border-left-danger">
            <span class="text-danger text-uppercase fz-12 fw-bold mb-1">{{ translate('Out of Stock') }}</span>
            <h2 class="fw-bold text-danger mb-1">{{ number_format($analytics['product']['out_of_stock']) }}</h2>
            <span class="fz-12 text-muted">{{ translate('Zero quantity in stock') }}</span>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-body shadow-sm border-0 h-100 border-left-warning">
            <span class="text-warning text-uppercase fz-12 fw-bold mb-1">{{ translate('Low Stock (<= 5)') }}</span>
            <h2 class="fw-bold text-warning mb-1">{{ number_format($analytics['product']['low_stock']) }}</h2>
            <span class="fz-12 text-muted">{{ translate('Re-order threshold reached') }}</span>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-body shadow-sm border-0 h-100">
            <span class="text-muted text-uppercase fz-12 fw-semibold mb-1">{{ translate('Total Wishlisted Items') }}</span>
            <h2 class="fw-bold text-primary mb-1">{{ number_format($analytics['product']['total_wishlists']) }}</h2>
            <span class="fz-12 text-muted">{{ translate('Saved by buyers') }}</span>
        </div>
    </div>

    <!-- Review Health -->
    <div class="col-lg-6">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-transparent border-0">
                <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                    <i class="tio-star text-warning"></i>
                    {{ translate('Product Reviews & Rating Health') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-4 mb-3">
                    <div class="text-center p-3 bg-light rounded">
                        <h1 class="fw-bold text-warning mb-0 display-4">{{ $analytics['product']['avg_rating'] }}</h1>
                        <span class="fz-12 text-muted">{{ translate('Avg Rating (out of 5)') }}</span>
                    </div>
                    <div>
                        <p class="mb-1"><strong>{{ number_format($analytics['product']['total_reviews']) }}</strong> {{ translate('Total Customer Reviews') }}</p>
                        <p class="mb-1 text-warning"><i class="tio-time"></i> <strong>{{ number_format($analytics['product']['unapproved_reviews']) }}</strong> {{ translate('Reviews Pending Moderation') }}</p>
                        <p class="mb-0 text-danger"><i class="tio-error-outlined"></i> <strong>{{ number_format($analytics['product']['one_star_reviews']) }}</strong> {{ translate('1-Star Critical Reviews') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rating Distribution -->
    <div class="col-lg-6">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-transparent border-0">
                <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                    <i class="tio-chart-pie-1 text-info"></i>
                    {{ translate('Rating Distribution (1 - 5 Stars)') }}
                </h5>
            </div>
            <div class="card-body">
                <div id="apex-product-ratings-chart" style="min-height: 200px;"></div>
            </div>
        </div>
    </div>
</div>
