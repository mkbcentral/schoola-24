<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('other_recipes', function (Blueprint $table) {
            $table->unsignedBigInteger('school_id')->nullable()->after('id');
            $table->unsignedBigInteger('school_year_id')->nullable()->after('school_id');
        });
    }

    public function down(): void
    {
        Schema::table('other_recipes', function (Blueprint $table) {
            $table->dropColumn(['school_id', 'school_year_id']);
        });
    }
};
