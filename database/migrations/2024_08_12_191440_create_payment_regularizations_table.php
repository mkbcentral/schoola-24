<?php

use App\Models\ClassRoom;
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
        Schema::create('payment_regularizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('month');
            $table->float('amount')->default(0);
            $table->foreignIdFor(ClassRoom::class)->constrained();
            $table->$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_regularizations');
    }
};
