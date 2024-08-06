<?php

use App\Models\CategoryRegistrationFee;
use App\Models\Option;
use App\Models\SchoolYear;
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
        Schema::create('registration_fees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('amount', 16)->nullable();
            $table->string('currency')->nullable();
            $table->foreignIdFor(Option::class)->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignIdFor(CategoryRegistrationFee::class)
                ->cascadeOnUpdate()
                ->cascadeOnDelete()
                ->constrained();
            $table->foreignIdFor(SchoolYear::class)
                ->cascadeOnUpdate()
                ->cascadeOnDelete()
                ->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_fees');
    }
};
