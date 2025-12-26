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
        Schema::table('users', function (Blueprint $table) {
            $table->string('sex')->nullable();
            $table->date('birth_date')->nullable();
            $table->boolean('is_profile_completed')->default(false);
            $table->timestamp('last_login')->nullable();
            $table->string('additional_phone', 25)->after('phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('sex');
            $table->dropColumn('birth_date');
            $table->dropColumn('is_profile_completed');
            $table->dropColumn('last_login');
            $table->dropColumn('additional_phone');
        });
    }
};
