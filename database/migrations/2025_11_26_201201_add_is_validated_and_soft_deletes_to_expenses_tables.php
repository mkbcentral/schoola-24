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
        // Ajouter is_validated et soft deletes à expense_fees
        Schema::table('expense_fees', function (Blueprint $table) {
            $table->boolean('is_validated')->default(false)->after('currency');
            $table->softDeletes();
        });

        // Ajouter is_validated et soft deletes à other_expenses
        Schema::table('other_expenses', function (Blueprint $table) {
            $table->boolean('is_validated')->default(false)->after('currency');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expense_fees', function (Blueprint $table) {
            $table->dropColumn('is_validated');
            $table->dropSoftDeletes();
        });

        Schema::table('other_expenses', function (Blueprint $table) {
            $table->dropColumn('is_validated');
            $table->dropSoftDeletes();
        });
    }
};
