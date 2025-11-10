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
        Schema::create('article_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('action', ['created', 'updated', 'deleted', 'stock_adjusted'])->comment('Type d\'action effectuée');
            $table->json('old_values')->nullable()->comment('Anciennes valeurs avant modification');
            $table->json('new_values')->nullable()->comment('Nouvelles valeurs après modification');
            $table->foreignId('user_id')->constrained()->comment('Utilisateur ayant effectué l\'action');
            $table->string('ip_address', 45)->nullable()->comment('Adresse IP de l\'utilisateur');
            $table->string('user_agent')->nullable()->comment('Navigateur de l\'utilisateur');
            $table->foreignId('school_id')->constrained();
            $table->timestamps();

            // Index pour les recherches fréquentes
            $table->index(['article_id', 'created_at']);
            $table->index('action');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_audits');
    }
};
