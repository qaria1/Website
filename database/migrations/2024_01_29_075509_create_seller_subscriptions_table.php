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
        Schema::create('seller_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('billing_type_id')->nullable();
            // $table->foreign('seller_id')->references('id')->on('sellers');
            // $table->foreign('plan_id')->references('id')->on('subscription_plans');

            $table->date('start_date')->nullable();
            $table->date('current_start')->nullable();
            $table->date('current_end')->nullable();

            $table->boolean('is_free')->default(false)->nullable();
            $table->boolean('is_trial')->default(false)->nullable();
            $table->boolean('status')->default(false)->nullable();

            // $table->date('upgraded_at')->nullable();
            // $table->date('upgraded_from_plan_id')->nullable();
            // $table->date('upgraded_to_plan_id')->nullable();
            // $table->date('downgraded_at')->nullable();
            // $table->date('downgraded_from_plan_id')->nullable();
            // $table->date('downgraded_to_plan_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_subscriptions');
    }
};
