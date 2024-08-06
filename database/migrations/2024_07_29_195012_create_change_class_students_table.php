<?php

use App\Models\ClassRoom;
use App\Models\Registration;
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
        Schema::create('change_class_students', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Registration::class)->constrained();
            $table->foreignIdFor(ClassRoom::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('change_class_students');
    }
};
