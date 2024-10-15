<?php

use App\Models\Payment;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sms_payments', function (Blueprint $table) {
            $table->id();
            $table->string('receiver');
            $table->string('message');
            $table->foreignIdFor(Payment::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_payments');
    }
};
