<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('article_stock_movements', function (Blueprint $table) {
            $table->date('closed_date')->nullable()->after('is_closed')->comment('Date de clôture du mouvement');
        });

        // Mettre à jour les mouvements déjà clôturés avec la date d'aujourd'hui
        /*
        DB::table('article_stock_movements')
            ->where('is_closed', true)
            ->whereNull('closed_date')
            ->update(['closed_date' => now()->toDateString()]);
            */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('article_stock_movements', function (Blueprint $table) {
            $table->dropColumn('closed_date');
        });
    }
};
