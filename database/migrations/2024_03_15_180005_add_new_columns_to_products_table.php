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
        Schema::table('products', function (Blueprint $table) {
            $table->string('reject_reason')->nullable();
            // $table->date('birth_date');
            $table->integer('seller_subscription_id');
            // $table->foreign('seller_subscription_id')->references('id')->on('seller_subscriptions');
            $table->date('lifetime_end_date')->nullable();
            $table->date('archived_at')->nullable();
            $table->boolean('is_lifetime_ended')->default(false);
            $table->boolean('checked_by_admin')->default(false);
            $table->boolean('checked_by_seller')->default(false);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('reject_reason');
            // $table->dropColumn('birth_date');
            $table->dropColumn('seller_subscription_id');
            $table->dropColumn('lifetime_end_date');
            $table->dropColumn('archived_at');
            $table->dropColumn('is_lifetime_ended');
            $table->dropColumn('checked_by_admin');
            $table->dropColumn('checked_by_seller');
        });
    }
};
