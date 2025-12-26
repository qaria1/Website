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
        Schema::create('plan_billings', function (Blueprint $table) {
            $table->id();
            $table->integer('price');
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->unsignedBigInteger('billing_type_id')->nullable();
            $table->boolean('available')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_billings');
    }
};
