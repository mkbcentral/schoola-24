<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('article_stock_movements', function (Blueprint $table) {
            $table->boolean('is_closed')->default(false)->after('note');
        });
    }

    public function down(): void
    {
        Schema::table('article_stock_movements', function (Blueprint $table) {
            $table->dropColumn('is_closed');
        });
    }
};
