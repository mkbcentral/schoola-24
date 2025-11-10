<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('article_audits', function (Blueprint $table) {
            // Ajouter une colonne pour référencer les mouvements de stock
            $table->foreignId('stock_movement_id')->nullable()->after('article_id')->constrained('article_stock_movements')->onDelete('cascade');
        });

        // Modifier l'enum pour ajouter les nouvelles actions
        DB::statement("ALTER TABLE article_audits MODIFY COLUMN action ENUM('created', 'updated', 'deleted', 'stock_adjusted', 'movement_created', 'movement_updated', 'movement_closed', 'movement_deleted') NOT NULL COMMENT 'Type d\\'action effectuée'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('article_audits', function (Blueprint $table) {
            $table->dropForeign(['stock_movement_id']);
            $table->dropColumn('stock_movement_id');
        });

        // Restaurer l'enum original
        DB::statement("ALTER TABLE article_audits MODIFY COLUMN action ENUM('created', 'updated', 'deleted', 'stock_adjusted') NOT NULL COMMENT 'Type d\\'action effectuée'");
    }
};
