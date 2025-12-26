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
        Schema::create('subscription_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('plan_id');
            $table->foreignId('seller_id');
            $table->double('price', 24, 3)->default(0);
            $table->integer('validity')->default(0);
            $table->string('payment_method', 191)->default('manual_payment_admin');
            $table->string('reference', 191)->nullable();
            $table->double('paid_amount', 24, 2);
            $table->json('package_details');
            $table->string('created_by', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_transactions');
    }
};