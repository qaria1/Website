<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupon_redemptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coupon_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('customer_id');
            $table->decimal('discount_amount', 12, 2)->default(0.00);
            $table->decimal('seller_bearer_amount', 12, 2)->default(0.00);
            $table->decimal('inhouse_bearer_amount', 12, 2)->default(0.00);
            $table->timestamp('redeemed_at')->useCurrent();
            $table->timestamps();

            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->index(['customer_id', 'coupon_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_redemptions');
    }
};
