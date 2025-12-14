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
        Schema::table('registration_derogations', function (Blueprint $table) {
            $table->boolean('is_monthly')->default(false)->after('end_date');
            $table->date('month_date')->nullable()->after('is_monthly');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registration_derogations', function (Blueprint $table) {
            $table->dropColumn(['is_monthly', 'month_date']);
        });
    }
};
