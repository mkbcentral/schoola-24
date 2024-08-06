<?php

use App\Enums\SchoolAppEnum;
use App\Enums\SchoolEnum;
use App\Models\Role;
use App\Models\School;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_on_line')->default(false);
            $table->foreignIdFor(Role::class)->nullable()
                ->cascadeOnUpdate()
                ->cascadeOnDelete()
                ->constrained();
            $table->foreignIdFor(School::class)->nullable()
                ->cascadeOnUpdate()
                ->cascadeOnDelete()
                ->constrained();
            $table->enum('app_status', SchoolAppEnum::getValues())
                ->default(SchoolAppEnum::IS_FREE)
                ->after('logo');
            $table->enum('school_status', SchoolEnum::getValues())
                ->default(SchoolEnum::PENDING)->after('app_status');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};