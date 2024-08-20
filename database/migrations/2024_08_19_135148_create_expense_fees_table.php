<?php

use App\Models\CategoryExpense;
use App\Models\CategoryFee;
use App\Models\SchoolYear;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expense_fees', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->string('month');
            $table->float('amount')->default(0);
            $table->string('currency');
            $table->foreignIdFor(CategoryExpense::class)->constrained();
            $table->foreignIdFor(CategoryFee::class)->constrained();
            $table->foreignIdFor(SchoolYear::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_fees');
    }
};
