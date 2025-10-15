<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table catalogue des articles
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('reference')->nullable();
            $table->string('unit')->nullable()->comment('Unité de mesure, ex: kg, L, pcs...');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('school_id')->nullable();
            $table->unsignedBigInteger('school_year_id')->nullable();
            $table->timestamps();
        });

        // Table des mouvements de stock (entrées/sorties)
        Schema::create('article_stock_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('article_id');
            $table->enum('type', ['in', 'out']);
            $table->integer('quantity');
            $table->date('movement_date');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('school_id')->nullable();
            $table->unsignedBigInteger('school_year_id')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_stock_movements');
        Schema::dropIfExists('articles');
    }
};
