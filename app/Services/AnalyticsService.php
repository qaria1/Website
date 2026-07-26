<?php

namespace App\Services;

use App\Models\AdminWallet;
use App\Models\Chatting;
use App\Models\CustomerWallet;
use App\Models\CustomerWalletHistory;
use App\Models\DeliveryMan;
use App\Models\DeliverymanWallet;
use App\Models\LoyaltyPointTransaction;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderTransaction;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\RefundRequest;
use App\Models\Review;
use App\Models\Seller;
use App\Models\SellerSubscription;
use App\Models\SellerWaitingList;
use App\Models\SellerWallet;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionTransaction;
use App\Models\SupportTicket;
use App\User;
use App\Models\Wishlist;
use App\Models\WithdrawRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    /**
     * Helper to apply date filters to standard queries.
     */
    private function applyDateFilter($query, array $dateRange = [], string $column = 'created_at')
    {
        if (!empty($dateRange['from']) && !empty($dateRange['to'])) {
            return $query->whereBetween($column, [
                Carbon::parse($dateRange['from'])->startOfDay(),
                Carbon::parse($dateRange['to'])->endOfDay()
            ]);
        }
        return $query;
    }

    /**
     * 1. Hero KPI Global Summary
     */
    public function getGlobalSummary(array $dateRange = []): array
    {
        $deliveredOrdersQuery = $this->applyDateFilter(Order::where('order_status', 'delivered'), $dateRange);
        $totalGrossRevenue = (float)$deliveredOrdersQuery->sum('order_amount');

        $allOrdersQuery = $this->applyDateFilter(Order::query(), $dateRange);
        $totalOrders = $allOrdersQuery->count();

        // Calculate dynamic real admin earnings (Commission + Subscriptions + In-House Sales + Delivery Fees)
        $commissionEarned = (float)$this->applyDateFilter(OrderTransaction::query(), $dateRange)->sum('admin_commission');
        $subscriptionEarned = (float)$this->applyDateFilter(SubscriptionTransaction::query(), $dateRange)->sum('paid_amount');
        $inhouseEarned = (float)$this->applyDateFilter(OrderTransaction::where('seller_is', 'admin'), $dateRange)->sum('order_amount');
        $deliveryEarned = (float)$this->applyDateFilter(OrderTransaction::query(), $dateRange)->sum('delivery_charge');

        $netAdminEarning = $commissionEarned + $subscriptionEarned + $inhouseEarned + $deliveryEarned;

        $activeVendors = Seller::where('status', 'approved')->count();
        $totalVendors = Seller::count();

        $activeCustomers = User::where('is_active', 1)->count();
        $totalCustomers = User::count();

        $pendingSellerWithdraw = WithdrawRequest::whereNotNull('seller_id')->where('approved', 0)->sum('amount');
        $pendingDeliveryWithdraw = WithdrawRequest::whereNotNull('delivery_man_id')->where('approved', 0)->sum('amount');
        $totalPendingWithdrawals = (float)($pendingSellerWithdraw + $pendingDeliveryWithdraw);

        // Action items count
        $pendingVendors = Seller::where('status', 'pending')->count();
        $pendingRefunds = RefundRequest::where('status', 'pending')->count();
        $lowStockProducts = ProductStock::where('qty', '<=', 5)->count();
        $pendingWithdrawCount = WithdrawRequest::where('approved', 0)->count();
        $actionAlertsCount = $pendingVendors + $pendingRefunds + $lowStockProducts + $pendingWithdrawCount;

        return [
            'total_gross_revenue' => $totalGrossRevenue,
            'net_admin_earning' => $netAdminEarning,
            'total_orders' => $totalOrders,
            'active_vendors' => $activeVendors,
            'total_vendors' => $totalVendors,
            'active_customers' => $activeCustomers,
            'total_customers' => $totalCustomers,
            'pending_withdrawals' => $totalPendingWithdrawals,
            'action_alerts_count' => $actionAlertsCount,
        ];
    }

    /**
     * 2. Financial Analytics
     */
    public function getFinancialAnalytics(array $dateRange = [], string $groupBy = 'month'): array
    {
        // Revenue Breakdown by Payment Method
        $ordersQuery = $this->applyDateFilter(Order::where('order_status', 'delivered'), $dateRange);

        $paymentMethods = [
            'cash' => (float)(clone $ordersQuery)->whereIn('payment_method', ['cash', 'pay_on_delivery'])->sum('order_amount'),
            'digital' => (float)(clone $ordersQuery)->whereNotIn('payment_method', ['cash', 'pay_on_delivery', 'pay_by_wallet', 'offline_payment'])->sum('order_amount'),
            'wallet' => (float)(clone $ordersQuery)->where('payment_method', 'pay_by_wallet')->sum('order_amount'),
            'offline' => (float)(clone $ordersQuery)->where('payment_method', 'offline_payment')->sum('order_amount'),
        ];

        // Payment Counts
        $paymentCounts = [
            'cash' => (int)(clone $ordersQuery)->whereIn('payment_method', ['cash', 'pay_on_delivery'])->count(),
            'digital' => (int)(clone $ordersQuery)->whereNotIn('payment_method', ['cash', 'pay_on_delivery', 'pay_by_wallet', 'offline_payment'])->count(),
            'wallet' => (int)(clone $ordersQuery)->where('payment_method', 'pay_by_wallet')->count(),
            'offline' => (int)(clone $ordersQuery)->where('payment_method', 'offline_payment')->count(),
        ];

        // Wallet Balances Overview
        $adminWallet = AdminWallet::where('admin_id', 1)->first();
        $sellerWalletSum = (float)SellerWallet::sum('total_earning') - (float)SellerWallet::sum('withdrawn');
        $deliverymanWalletSum = (float)DeliverymanWallet::sum('current_balance');
        $customerWalletSum = (float)CustomerWallet::sum('current_balance');

        $walletOverview = [
            'admin_inhouse' => (float)($adminWallet->inhouse_earning ?? 0),
            'admin_commission' => (float)($adminWallet->commission_earned ?? 0),
            'admin_delivery_charge' => (float)($adminWallet->delivery_charge_earned ?? 0),
            'admin_pending' => (float)($adminWallet->pending_amount ?? 0),
            'total_seller_wallet_balance' => max(0, $sellerWalletSum),
            'total_deliveryman_wallet_balance' => $deliverymanWalletSum,
            'total_customer_wallet_balance' => $customerWalletSum,
        ];

        // Withdrawals summary
        $withdrawSummary = [
            'pending_seller_withdraw_count' => WithdrawRequest::whereNotNull('seller_id')->where('approved', 0)->count(),
            'pending_seller_withdraw_amount' => (float)WithdrawRequest::whereNotNull('seller_id')->where('approved', 0)->sum('amount'),
            'approved_seller_withdraw_amount' => (float)WithdrawRequest::whereNotNull('seller_id')->where('approved', 1)->sum('amount'),
            'pending_delivery_withdraw_count' => WithdrawRequest::whereNotNull('delivery_man_id')->where('approved', 0)->count(),
            'pending_delivery_withdraw_amount' => (float)WithdrawRequest::whereNotNull('delivery_man_id')->where('approved', 0)->sum('amount'),
            'approved_delivery_withdraw_amount' => (float)WithdrawRequest::whereNotNull('delivery_man_id')->where('approved', 1)->sum('amount'),
        ];

        // Revenue & Commission Timeline
        $timelineData = $this->getFinancialTimeline($dateRange, $groupBy);

        return [
            'payment_methods' => $paymentMethods,
            'payment_counts' => $paymentCounts,
            'wallet_overview' => $walletOverview,
            'withdraw_summary' => $withdrawSummary,
            'timeline' => $timelineData,
        ];
    }

    private function getFinancialTimeline(array $dateRange = [], string $groupBy = 'month'): array
    {
        $from = !empty($dateRange['from']) ? $dateRange['from'] : Carbon::now()->startOfYear()->format('Y-m-d');
        $to = !empty($dateRange['to']) ? $dateRange['to'] : Carbon::now()->endOfYear()->format('Y-m-d');

        $labels = [];
        $inhouseData = [];
        $sellerData = [];
        $commissionData = [];
        $subscriptionData = [];

        if ($groupBy === 'day') {
            $period = Carbon::parse($from)->daysUntil($to);
            foreach ($period as $date) {
                $dayStr = $date->format('Y-m-d');
                $labels[] = $date->format('M d');
                $inhouseData[] = (float)OrderTransaction::where('seller_is', 'admin')->whereDate('created_at', $dayStr)->sum('order_amount');
                $sellerData[] = (float)OrderTransaction::where('seller_is', 'seller')->whereDate('created_at', $dayStr)->sum('order_amount');
                $commissionData[] = (float)OrderTransaction::whereDate('created_at', $dayStr)->sum('admin_commission');
                $subscriptionData[] = (float)SubscriptionTransaction::whereDate('created_at', $dayStr)->sum('paid_amount');
            }
        } else {
            // Month wise default
            for ($m = 1; $m <= 12; $m++) {
                $labels[] = Carbon::create(null, $m, 1)->format('M');
                $inhouseData[] = (float)OrderTransaction::where('seller_is', 'admin')->whereYear('created_at', Carbon::parse($from)->year)->whereMonth('created_at', $m)->sum('order_amount');
                $sellerData[] = (float)OrderTransaction::where('seller_is', 'seller')->whereYear('created_at', Carbon::parse($from)->year)->whereMonth('created_at', $m)->sum('order_amount');
                $commissionData[] = (float)OrderTransaction::whereYear('created_at', Carbon::parse($from)->year)->whereMonth('created_at', $m)->sum('admin_commission');
                $subscriptionData[] = (float)SubscriptionTransaction::whereYear('created_at', Carbon::parse($from)->year)->whereMonth('created_at', $m)->sum('paid_amount');
            }
        }

        return [
            'labels' => $labels,
            'inhouse' => $inhouseData,
            'seller' => $sellerData,
            'commission' => $commissionData,
            'subscription' => $subscriptionData,
        ];
    }

    /**
     * 3. Order Analytics
     */
    public function getOrderAnalytics(array $dateRange = [], string $groupBy = 'month'): array
    {
        $orderQuery = $this->applyDateFilter(Order::query(), $dateRange);

        $statuses = [
            'pending' => (clone $orderQuery)->where('order_status', 'pending')->count(),
            'confirmed' => (clone $orderQuery)->where('order_status', 'confirmed')->count(),
            'processing' => (clone $orderQuery)->where('order_status', 'processing')->count(),
            'out_for_delivery' => (clone $orderQuery)->where('order_status', 'out_for_delivery')->count(),
            'delivered' => (clone $orderQuery)->where('order_status', 'delivered')->count(),
            'canceled' => (clone $orderQuery)->where('order_status', 'canceled')->count(),
            'returned' => (clone $orderQuery)->where('order_status', 'returned')->count(),
            'failed' => (clone $orderQuery)->where('order_status', 'failed')->count(),
        ];

        $totalCount = array_sum($statuses);
        $deliveredCount = $statuses['delivered'];

        // Average Order Value (AOV)
        $deliveredQuery = (clone $orderQuery)->where('order_status', 'delivered');
        $deliveredTotalAmount = (float)$deliveredQuery->sum('order_amount');
        $aov = $deliveredCount > 0 ? round($deliveredTotalAmount / $deliveredCount, 2) : 0;

        // Physical vs Digital Products in Orders
        $detailsQuery = OrderDetail::whereHas('order', function($q) use ($dateRange) {
            $this->applyDateFilter($q, $dateRange);
        });
        $physicalCount = (clone $detailsQuery)->where('product_type', 'physical')->count();
        $digitalCount = (clone $detailsQuery)->where('product_type', 'digital')->count();

        // Conversion Rate
        $deliveryRate = $totalCount > 0 ? round(($deliveredCount / $totalCount) * 100, 1) : 0;

        return [
            'status_counts' => $statuses,
            'total_orders' => $totalCount,
            'delivered_orders' => $deliveredCount,
            'aov' => $aov,
            'delivery_rate' => $deliveryRate,
            'product_types' => [
                'physical' => $physicalCount,
                'digital' => $digitalCount,
            ],
        ];
    }

    /**
     * 4. Vendor & Subscription Analytics
     */
    public function getVendorAnalytics(array $dateRange = []): array
    {
        $totalVendors = Seller::count();
        $approvedVendors = Seller::where('status', 'approved')->count();
        $pendingVendors = Seller::where('status', 'pending')->count();
        $suspendedVendors = Seller::where('status', 'rejected')->count();

        // Subscriptions breakdown
        $plans = SubscriptionPlan::withCount(['subscriptions' => function($q) {
            $q->where('status', true);
        }])->get();

        $planBreakdown = [];
        foreach ($plans as $plan) {
            $planBreakdown[] = [
                'name' => $plan->name,
                'count' => $plan->subscriptions_count,
            ];
        }

        // Subscriptions expiring soon (< 30 days and < 7 days)
        $expiring30 = SellerSubscription::where('status', true)->whereBetween('current_end', [now(), now()->addDays(30)])->count();
        $expiring7 = SellerSubscription::where('status', true)->whereBetween('current_end', [now(), now()->addDays(7)])->count();

        // Waiting list
        $waitingListCount = SellerWaitingList::count();

        // Top 5 Vendors by Earning
        $topEarningVendors = SellerWallet::with(['seller.shop'])
            ->orderBy('total_earning', 'desc')
            ->take(5)
            ->get();

        return [
            'total' => $totalVendors,
            'approved' => $approvedVendors,
            'pending' => $pendingVendors,
            'suspended' => $suspendedVendors,
            'plan_breakdown' => $planBreakdown,
            'expiring_30_days' => $expiring30,
            'expiring_7_days' => $expiring7,
            'waiting_list_count' => $waitingListCount,
            'top_earning_vendors' => $topEarningVendors,
        ];
    }

    /**
     * 5. Customer Analytics
     */
    public function getCustomerAnalytics(array $dateRange = []): array
    {
        $totalCustomers = User::count();
        $activeCustomers = User::where('is_active', 1)->count();
        $blockedCustomers = User::where('is_active', 0)->count();

        // Repeat vs One-time customers
        $customerOrderCounts = Order::select('customer_id', DB::raw('COUNT(*) as total_orders'))
            ->whereNotNull('customer_id')
            ->groupBy('customer_id')
            ->get();

        $repeatCustomers = $customerOrderCounts->where('total_orders', '>', 1)->count();
        $oneTimeCustomers = $customerOrderCounts->where('total_orders', 1)->count();

        // Customer Lifetime Value (CLV Avg)
        $totalSpent = Order::where('order_status', 'delivered')->sum('order_amount');
        $clvAvg = $totalCustomers > 0 ? round($totalSpent / $totalCustomers, 2) : 0;

        // Loyalty Points Overview
        $loyaltyPointsIssued = LoyaltyPointTransaction::where('transaction_type', 'credit')->sum('credit');
        $loyaltyPointsRedeemed = LoyaltyPointTransaction::where('transaction_type', 'debit')->sum('debit');

        // Customer Wallets
        $totalWalletBalance = CustomerWallet::sum('current_balance');

        return [
            'total' => $totalCustomers,
            'active' => $activeCustomers,
            'blocked' => $blockedCustomers,
            'repeat_customers' => $repeatCustomers,
            'one_time_customers' => $oneTimeCustomers,
            'clv_avg' => $clvAvg,
            'loyalty_points_issued' => $loyaltyPointsIssued,
            'loyalty_points_redeemed' => $loyaltyPointsRedeemed,
            'total_wallet_balance' => $totalWalletBalance,
        ];
    }

    /**
     * 6. Product Analytics
     */
    public function getProductAnalytics(array $dateRange = []): array
    {
        $totalProducts = Product::count();
        $activeProducts = Product::where('status', 1)->count();
        $pendingProducts = Product::where('request_status', 0)->count();
        $outOfStock = ProductStock::where('qty', 0)->count();
        $lowStock = ProductStock::where('qty', '>', 0)->where('qty', '<=', 5)->count();

        // Review Health
        $avgRating = (float)Review::avg('rating');
        $totalReviews = Review::count();
        $unapprovedReviews = Review::where('status', 0)->count();
        $oneStarReviews = Review::where('rating', 1)->count();

        $ratingDistribution = [
            5 => Review::where('rating', 5)->count(),
            4 => Review::where('rating', 4)->count(),
            3 => Review::where('rating', 3)->count(),
            2 => Review::where('rating', 2)->count(),
            1 => Review::where('rating', 1)->count(),
        ];

        // Wishlists
        $totalWishlists = Wishlist::count();

        return [
            'total' => $totalProducts,
            'active' => $activeProducts,
            'pending' => $pendingProducts,
            'out_of_stock' => $outOfStock,
            'low_stock' => $lowStock,
            'avg_rating' => round($avgRating, 1),
            'total_reviews' => $totalReviews,
            'unapproved_reviews' => $unapprovedReviews,
            'one_star_reviews' => $oneStarReviews,
            'rating_distribution' => $ratingDistribution,
            'total_wishlists' => $totalWishlists,
        ];
    }

    /**
     * 7. Delivery Analytics
     */
    public function getDeliveryAnalytics(): array
    {
        $totalDeliverymen = DeliveryMan::count();
        $activeDeliverymen = DeliveryMan::where('is_active', 1)->count();

        $wallets = DeliverymanWallet::all();
        $totalCashInHand = (float)$wallets->sum('cash_in_hand');
        $totalPendingWithdraw = (float)$wallets->sum('pending_withdraw');
        $totalWithdrawn = (float)$wallets->sum('total_withdraw');

        $topDeliverymen = DeliveryMan::withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->take(5)
            ->get();

        return [
            'total' => $totalDeliverymen,
            'active' => $activeDeliverymen,
            'total_cash_in_hand' => $totalCashInHand,
            'total_pending_withdraw' => $totalPendingWithdraw,
            'total_withdrawn' => $totalWithdrawn,
            'top_deliverymen' => $topDeliverymen,
        ];
    }

    /**
     * 8. Refund & Support Analytics
     */
    public function getRefundSupportAnalytics(): array
    {
        // Refund Requests
        $pendingRefunds = RefundRequest::where('status', 'pending')->count();
        $approvedRefunds = RefundRequest::where('status', 'approved')->count();
        $rejectedRefunds = RefundRequest::where('status', 'rejected')->count();
        $refundedAmount = (float)RefundRequest::where('status', 'approved')->sum('amount');

        $totalDeliveredOrders = Order::where('order_status', 'delivered')->count();
        $refundRate = $totalDeliveredOrders > 0 ? round(($approvedRefunds / $totalDeliveredOrders) * 100, 1) : 0;

        // Support Tickets
        $openTickets = SupportTicket::where('status', 'open')->count();
        $closeTickets = SupportTicket::where('status', 'close')->count();
        $urgentTickets = SupportTicket::where('priority', 'urgent')->where('status', 'open')->count();

        // Chat conversations count
        $totalChats = Chatting::count();

        return [
            'pending_refunds' => $pendingRefunds,
            'approved_refunds' => $approvedRefunds,
            'rejected_refunds' => $rejectedRefunds,
            'refunded_amount' => $refundedAmount,
            'refund_rate' => $refundRate,
            'open_tickets' => $openTickets,
            'close_tickets' => $closeTickets,
            'urgent_tickets' => $urgentTickets,
            'total_chats' => $totalChats,
        ];
    }

    /**
     * 9. Activity Feed & Action Alerts
     */
    public function getActivityAndAlerts(): array
    {
        $alerts = [
            'pending_vendors' => Seller::where('status', 'pending')->count(),
            'pending_seller_withdraws' => WithdrawRequest::whereNotNull('seller_id')->where('approved', 0)->count(),
            'pending_delivery_withdraws' => WithdrawRequest::whereNotNull('delivery_man_id')->where('approved', 0)->count(),
            'pending_refunds' => RefundRequest::where('status', 'pending')->count(),
            'low_stock_products' => ProductStock::where('qty', '<=', 5)->count(),
            'expiring_subscriptions' => SellerSubscription::where('status', true)->whereBetween('current_end', [now(), now()->addDays(7)])->count(),
            'urgent_support_tickets' => SupportTicket::where('priority', 'urgent')->where('status', 'open')->count(),
        ];

        $latestOrders = Order::with('customer')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        return [
            'alerts' => $alerts,
            'latest_orders' => $latestOrders,
        ];
    }
}
