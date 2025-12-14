<?php

use App\Enums\SchoolAppEnum;
use App\Enums\SchoolEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update existing data to match enum values
        DB::table('schools')->update([
            'app_status' => SchoolAppEnum::IS_FREE,
        ]);

        DB::table('schools')
            ->whereIn('school_status', ['active', 'inactive', 'suspended'])
            ->update(['school_status' => SchoolEnum::PENDING]);

        // Then change column types to enum
        Schema::table('schools', function (Blueprint $table) {
            // Change app_status to enum
            $table->enum('app_status', SchoolAppEnum::getValues())
                ->default(SchoolAppEnum::IS_FREE)
                ->change();

            // Change school_status to enum
            $table->enum('school_status', SchoolEnum::getValues())
                ->default(SchoolEnum::PENDING)
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            // Revert app_status to string
            $table->string('app_status')
                ->default(SchoolAppEnum::IS_FREE)
                ->change();

            // Revert school_status to string
            $table->string('school_status')
                ->default(SchoolEnum::PENDING)
                ->change();
        });
    }
};
