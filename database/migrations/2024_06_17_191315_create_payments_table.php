<?php

use App\Models\Rate;
use App\Models\Registration;
use App\Models\ScolarFee;
use App\Models\User;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number');
            $table->string('month');
            $table->foreignIdFor(Registration::class)->constrained();
            $table->foreignIdFor(ScolarFee::class)->constrained();
            $table->foreignIdFor(Rate::class)->constrained();
            $table->foreignIdFor(User::class)->constrained();
            $table->boolean('is_paid')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
