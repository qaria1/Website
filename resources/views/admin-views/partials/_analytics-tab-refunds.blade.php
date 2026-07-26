<div class="row g-3">
    <!-- Header -->
    <div class="col-12">
        <div class="card card-body shadow-sm border-0 d-flex flex-row justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0 d-flex align-items-center gap-2">
                <i class="tio-history text-primary fz-20"></i>
                {{ translate('Refunds & Support Operations Analytics') }}
            </h5>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.refund-section.refund.list', ['pending']) }}" class="btn btn-outline-warning btn-sm rounded-pill">
                    <i class="tio-time"></i> {{ translate('Pending Refunds') }}
                </a>
                <a href="{{ route('admin.support-ticket.view') }}" class="btn btn-outline-info btn-sm rounded-pill">
                    <i class="tio-chat"></i> {{ translate('Support Tickets') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Refund Stats -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-body shadow-sm border-0 h-100 border-left-warning">
            <span class="text-warning text-uppercase fz-12 fw-bold mb-1">{{ translate('Pending Refunds') }}</span>
            <h2 class="fw-bold text-warning mb-1">{{ number_format($analytics['refund_support']['pending_refunds']) }}</h2>
            <span class="fz-12 text-muted">{{ translate('Action required by admin') }}</span>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-body shadow-sm border-0 h-100">
            <span class="text-muted text-uppercase fz-12 fw-semibold mb-1">{{ translate('Approved Refund Amount') }}</span>
            <h2 class="fw-bold text-success mb-1">
                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $analytics['refund_support']['refunded_amount']), currencyCode: getCurrencyCode()) }}
            </h2>
            <span class="fz-12 text-muted">{{ $analytics['refund_support']['approved_refunds'] }} {{ translate('approved requests') }}</span>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-body shadow-sm border-0 h-100">
            <span class="text-muted text-uppercase fz-12 fw-semibold mb-1">{{ translate('Refund Rate %') }}</span>
            <h2 class="fw-bold text-danger mb-1">{{ $analytics['refund_support']['refund_rate'] }}%</h2>
            <span class="fz-12 text-muted">{{ translate('Refunds vs Delivered orders') }}</span>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-body shadow-sm border-0 h-100">
            <span class="text-muted text-uppercase fz-12 fw-semibold mb-1">{{ translate('Open Support Tickets') }}</span>
            <h2 class="fw-bold text-primary mb-1">{{ number_format($analytics['refund_support']['open_tickets']) }}</h2>
            @if($analytics['refund_support']['urgent_tickets'] > 0)
                <span class="badge badge-soft-danger">{{ $analytics['refund_support']['urgent_tickets'] }} {{ translate('Urgent priority') }}</span>
            @else
                <span class="fz-12 text-muted">{{ translate('Customer support tickets') }}</span>
            @endif
        </div>
    </div>

    <!-- Support Tickets & Chat Breakdown -->
    <div class="col-lg-6">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-transparent border-0">
                <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                    <i class="tio-support text-info"></i>
                    {{ translate('Support Tickets Overview') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded text-center">
                            <span class="text-muted fz-13 d-block mb-1">{{ translate('Open / Unresolved') }}</span>
                            <h3 class="fw-bold text-warning mb-0">{{ number_format($analytics['refund_support']['open_tickets']) }}</h3>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded text-center">
                            <span class="text-muted fz-13 d-block mb-1">{{ translate('Closed / Solved') }}</span>
                            <h3 class="fw-bold text-success mb-0">{{ number_format($analytics['refund_support']['close_tickets']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Messages Activity -->
    <div class="col-lg-6">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-transparent border-0">
                <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                    <i class="tio-chat-outlined text-primary"></i>
                    {{ translate('Chat & Engagement Activity') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="p-4 bg-light rounded text-center">
                    <span class="text-muted fz-13 d-block mb-2">{{ translate('Total Messages Exchanged') }}</span>
                    <h2 class="fw-bold text-primary mb-0">{{ number_format($analytics['refund_support']['total_chats']) }}</h2>
                    <small class="text-muted mt-2 d-block">{{ translate('Direct communications between buyers, sellers, and delivery staff') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
