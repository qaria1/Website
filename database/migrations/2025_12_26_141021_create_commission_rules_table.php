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
        Schema::create('commission_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->decimal('min_price', 15, 2);
            $table->decimal('max_price', 15, 2)->nullable();
            $table->decimal('commission_percent', 5, 2);
            $table->timestamps();
            $table->unique(['seller_id', 'min_price', 'max_price'], 'vendor_price_unique');
            $table->index(['seller_id', 'min_price']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_rules');
    }
};
