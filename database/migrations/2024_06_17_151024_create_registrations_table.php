<?php

use App\Models\ClassRoom;
use App\Models\Rate;
use App\Models\RegistrationFee;
use App\Models\SchoolYear;
use App\Models\Student;
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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('registration_number')->nullable();
            $table->foreignIdFor(Student::class)
                ->cascadeOnUpdate()
                ->cascadeOnDelete()
                ->constrained();
            $table->foreignIdFor(RegistrationFee::class)
                ->cascadeOnUpdate()
                ->cascadeOnDelete()
                ->constrained();
            $table->foreignIdFor(ClassRoom::class)
                ->cascadeOnUpdate()
                ->cascadeOnDelete()
                ->constrained();
            $table->foreignIdFor(SchoolYear::class)->constrained();
            $table->foreignIdFor(Rate::class)->constrained();
            $table->boolean('is_registered')->default(false);
            $table->boolean('is_old')->default(false);
            $table->boolean('abandoned')->default(false);
            $table->boolean('class_changed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
