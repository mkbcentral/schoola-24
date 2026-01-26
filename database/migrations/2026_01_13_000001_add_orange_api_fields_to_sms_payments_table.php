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
        Schema::table('sms_payments', function (Blueprint $table) {
            $table->string('resource_id')->nullable()->after('message')->comment('Orange SMS API resource ID pour tracking');
            $table->string('status')->default('sent')->after('resource_id'); // sent, delivered, failed, pending
            $table->timestamp('sent_at')->nullable()->after('status');
            $table->string('delivery_status')->nullable()->after('sent_at'); // DeliveredToTerminal, etc.
            $table->timestamp('delivered_at')->nullable()->after('sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sms_payments', function (Blueprint $table) {
            $table->dropColumn(['resource_id', 'status', 'sent_at', 'delivered_at', 'delivery_status']);
        });
    }
};
