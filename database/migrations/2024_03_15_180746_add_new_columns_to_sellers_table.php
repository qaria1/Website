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
        Schema::table('sellers', function (Blueprint $table) {
            $table->string('sex')->nullable();
            $table->integer('age')->nullable();
            $table->string('reject_reason')->nullable();
            $table->string('suspend_reason')->nullable();
            $table->boolean('checked')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->dropColumn('sex');
            $table->dropColumn('age');
            $table->dropColumn('reject_reason');
            $table->dropColumn('suspend_reason');
            $table->dropColumn('checked');
        });
    }
};
