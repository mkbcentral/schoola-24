<?php

use App\Models\CategoryFee;
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
        Schema::create('scolar_fees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('amount', 16)->nullable();
            $table->string('currency')->nullable();
            $table->foreignIdFor(CategoryFee::class)->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignIdFor(ClassRoom::class)->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scolar_fees');
    }
};
