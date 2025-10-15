<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('other_recipes', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('created_at');
            $table->date('end_date')->nullable()->after('start_date');
            $table->boolean('is_period')->default(false)->after('end_date');
        });
    }

    public function down(): void
    {
        Schema::table('other_recipes', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date', 'is_period']);
        });
    }
};
