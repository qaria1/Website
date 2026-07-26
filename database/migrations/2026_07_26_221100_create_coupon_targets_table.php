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
        Schema::create('coupon_targets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coupon_id');
            $table->enum('target_type', ['product', 'category', 'customer', 'customer_group'])->default('product');
            $table->unsignedBigInteger('target_id');
            $table->tinyInteger('is_exclusion')->default(0);
            $table->timestamps();

            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');
            $table->index(['coupon_id', 'target_type', 'target_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_targets');
    }
};
