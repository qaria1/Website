<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\CouponRedemption;
use App\Models\CouponTarget;
use App\Models\Order;
use App\Utils\CartManager;
use App\Utils\Helpers;

class CouponValidationService
{
    /**
     * Validate a coupon code and calculate applicable discount in ETB.
     *
     * @param string $code
     * @param mixed $user Customer object or ID
     * @param iterable $cartItems Collection or array of cart items
     * @return array Response structure containing status, messages, discount, and coupon instance
     */
    public function validateAndCalculate(string $code, $user, $cartItems): array
    {
        $customerId = is_object($user) ? $user->id : (is_numeric($user) ? (int)$user : 0);

        $coupon = Coupon::where(['code' => $code])
            ->where('status', 1)
            ->whereDate('start_date', '<=', date('Y-m-d'))
            ->whereDate('expire_date', '>=', date('Y-m-d'))
            ->first();

        if (!$coupon) {
            return [
                'status' => 0,
                'message' => translate('invalid_coupon'),
                'discount' => 0,
                'coupon' => null
            ];
        }

        // Global Campaign Limit Check
        if ($coupon->limit_total > 0 && $coupon->total_used_count >= $coupon->limit_total) {
            return [
                'status' => 0,
                'message' => translate('coupon_limit_is_over'),
                'discount' => 0,
                'coupon' => null
            ];
        }

        // Per-Customer Usage Limit Check
        if ($coupon->coupon_type !== 'first_order' && $coupon->limit > 0 && $customerId > 0) {
            $userUsageCount = Order::where(['customer_id' => $customerId, 'coupon_code' => $code])
                ->groupBy('order_group_id')
                ->get()
                ->count();

            if ($userUsageCount >= $coupon->limit) {
                return [
                    'status' => 0,
                    'message' => translate('coupon_limit_is_over_for_this_user'),
                    'discount' => 0,
                    'coupon' => null
                ];
            }
        }

        // First Order Type Validation
        if ($coupon->coupon_type === 'first_order') {
            if ($customerId > 0) {
                $previousOrders = Order::where('customer_id', $customerId)->count();
                if ($previousOrders > 0) {
                    return [
                        'status' => 0,
                        'message' => translate('sorry_this_coupon_is_not_valid_for_this_user'),
                        'discount' => 0,
                        'coupon' => null
                    ];
                }
            }
        }

        // Customer Eligibility Validation
        if ($coupon->customer_id != 0 && $coupon->customer_id != $customerId) {
            return [
                'status' => 0,
                'message' => translate('sorry_this_coupon_is_not_valid_for_this_user'),
                'discount' => 0,
                'coupon' => null
            ];
        }

        // Target Product/Category Scoping Check
        $targetProductIds = CouponTarget::where('coupon_id', $coupon->id)
            ->where('target_type', 'product')
            ->where('is_exclusion', 0)
            ->pluck('target_id')
            ->toArray();

        $targetCategoryIds = CouponTarget::where('coupon_id', $coupon->id)
            ->where('target_type', 'category')
            ->where('is_exclusion', 0)
            ->pluck('target_id')
            ->toArray();

        $applicableSubtotal = 0;
        $applicableShippingFee = 0;
        $hasTargetRestrictions = !empty($targetProductIds) || !empty($targetCategoryIds);

        foreach ($cartItems as $cart) {
            $cartSellerId = $cart['seller_id'] ?? ($cart->seller_id ?? null);
            $cartSellerIs = $cart['seller_is'] ?? ($cart->seller_is ?? null);
            $productId = $cart['product_id'] ?? ($cart->product_id ?? null);
            $categoryId = $cart['category_id'] ?? ($cart->product->category_id ?? null);

            // Vendor match check
            $vendorMatches = ($coupon->seller_id == 0 || $coupon->seller_id === '0')
                || (is_null($coupon->seller_id) && $cartSellerIs === 'admin')
                || ($coupon->seller_id == $cartSellerId && $cartSellerIs === 'seller');

            if ($vendorMatches) {
                // Category & Product Scope check
                if ($hasTargetRestrictions) {
                    $matchesProduct = !empty($targetProductIds) && in_array($productId, $targetProductIds);
                    $matchesCategory = !empty($targetCategoryIds) && in_array($categoryId, $targetCategoryIds);
                    if (!$matchesProduct && !$matchesCategory) {
                        continue;
                    }
                }

                $itemPrice = $cart['price'] ?? ($cart->price ?? 0);
                $itemQty = $cart['quantity'] ?? ($cart->quantity ?? 1);
                $itemShipping = $cart['shipping_cost'] ?? ($cart->shipping_cost ?? 0);

                $applicableSubtotal += ($itemPrice * $itemQty);
                $applicableShippingFee += $itemShipping;
            }
        }

        // Minimum Purchase Subtotal Check in ETB
        if ($applicableSubtotal < $coupon->min_purchase) {
            return [
                'status' => 0,
                'message' => translate('minimum_purchase_requirement_not_met'),
                'discount' => 0,
                'coupon' => null
            ];
        }

        // Discount Calculation
        $discount = 0;

        if (in_array($coupon->coupon_type, ['discount_on_purchase', 'first_order'])) {
            if ($coupon->discount_type === 'percentage') {
                $calculatedDiscount = ($applicableSubtotal / 100) * $coupon->discount;
                $discount = ($coupon->max_discount > 0 && $calculatedDiscount > $coupon->max_discount)
                    ? $coupon->max_discount
                    : $calculatedDiscount;
            } else {
                $discount = $coupon->discount;
            }
            // Discount cannot exceed subtotal
            if ($discount > $applicableSubtotal) {
                $discount = $applicableSubtotal;
            }
        } elseif ($coupon->coupon_type === 'free_delivery') {
            $discount = $applicableShippingFee;
        }

        return [
            'status' => 1,
            'message' => translate('coupon_applied_successfully'),
            'discount' => round($discount, 2),
            'applicable_subtotal' => round($applicableSubtotal, 2),
            'coupon' => $coupon
        ];
    }

    /**
     * Record immutable redemption ledger entry and increment counters.
     *
     * @param Coupon $coupon
     * @param int $orderId
     * @param int $customerId
     * @param float $discountAmount
     * @param string $bearer
     * @return CouponRedemption
     */
    public function recordRedemption(Coupon $coupon, int $orderId, int $customerId, float $discountAmount, string $bearer = 'inhouse'): CouponRedemption
    {
        $sellerShare = 0;
        $inhouseShare = 0;

        if ($bearer === 'seller') {
            $sellerShare = $discountAmount;
        } else {
            $inhouseShare = $discountAmount;
        }

        // Increment counter
        $coupon->increment('total_used_count');

        return CouponRedemption::create([
            'coupon_id' => $coupon->id,
            'order_id' => $orderId,
            'customer_id' => $customerId,
            'discount_amount' => $discountAmount,
            'seller_bearer_amount' => $sellerShare,
            'inhouse_bearer_amount' => $inhouseShare,
            'redeemed_at' => now(),
        ]);
    }
}
