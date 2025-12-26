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
        Schema::create('seller_product_counts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('count_left')->nullable();
            $table->unsignedBigInteger('seller_subscription_id');
            // $table->foreign('seller_subscription_id')->references('id')->on('seller_subscriptions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_product_counts');
    }
};
