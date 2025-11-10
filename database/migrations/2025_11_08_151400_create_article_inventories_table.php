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
        Schema::create('article_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->integer('expected_quantity')->comment('Quantité théorique en stock');
            $table->integer('actual_quantity')->comment('Quantité réelle comptée');
            $table->integer('difference')->comment('Écart (actual - expected)');
            $table->enum('status', ['conforme', 'excedent', 'manquant'])->comment('Statut de l\'inventaire');
            $table->date('inventory_date')->comment('Date de l\'inventaire');
            $table->text('note')->nullable()->comment('Notes ou observations');
            $table->foreignId('user_id')->constrained()->comment('Utilisateur ayant effectué l\'inventaire');
            $table->foreignId('school_id')->constrained();
            $table->foreignId('school_year_id')->constrained();
            $table->timestamps();

            // Index pour les recherches fréquentes
            $table->index(['article_id', 'inventory_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_inventories');
    }
};
