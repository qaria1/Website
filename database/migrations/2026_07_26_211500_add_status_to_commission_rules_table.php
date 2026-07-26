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
        if (!Schema::hasColumn('commission_rules', 'status')) {
            Schema::table('commission_rules', function (Blueprint $table) {
                $table->tinyInteger('status')->default(1)->after('commission_percent');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('commission_rules', 'status')) {
            Schema::table('commission_rules', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
