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
        Schema::table('seller_subscriptions', function (Blueprint $table) {
            $table->integer('max_product_lifecycle')->nullable();
            $table->string('max_product_upload')->default('unlimited');
            $table->boolean('discount')->default(false);
            $table->boolean('product_top_search')->default(false);
            $table->boolean('item_verification')->default(false);
            $table->boolean('product_photoshoot')->default(false);
            $table->boolean('free_delivery')->default(false);
            $table->string('available_vendors')->nullable();
            $table->tinyInteger('total_package_renewed')->default(0);

            $table->double('price', 24, 3);
            $table->integer('validity')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seller_subscriptions', function (Blueprint $table) {
            $table->dropColumn('max_product_lifecycle');
            $table->dropColumn('max_product_upload');
            $table->dropColumn('discount');
            $table->dropColumn('product_top_search');
            $table->dropColumn('item_verification');
            $table->dropColumn('product_photoshoot');
            $table->dropColumn('free_delivery');
            $table->dropColumn('available_vendors');
            $table->dropColumn('total_package_renewed');

            $table->dropColumn('price', 24, 3);
            $table->dropColumn('validity');
        });
    }
};
