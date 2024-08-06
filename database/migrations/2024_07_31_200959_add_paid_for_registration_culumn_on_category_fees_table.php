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
        Schema::table('category_fees', function (Blueprint $table) {
            $table->boolean('is_paid_for_registration')->default(false)->after('is_paid_in_installment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_fees', function (Blueprint $table) {
            $table->dropColumn('is_paid_for_registration');
        });
    }
};
