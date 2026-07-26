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
        Schema::table('coupons', function (Blueprint $table) {
            if (!Schema::hasColumn('coupons', 'limit_total')) {
                $table->integer('limit_total')->default(0)->after('limit')->comment('0 = unlimited overall campaign redemptions');
            }
            if (!Schema::hasColumn('coupons', 'total_used_count')) {
                $table->integer('total_used_count')->default(0)->after('limit_total')->comment('Atomic usage counter');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            if (Schema::hasColumn('coupons', 'limit_total')) {
                $table->dropColumn('limit_total');
            }
            if (Schema::hasColumn('coupons', 'total_used_count')) {
                $table->dropColumn('total_used_count');
            }
        });
    }
};
