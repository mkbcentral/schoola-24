<?php

use App\Models\CategoryExpense;
use App\Models\OtherSourceExpense;
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
        Schema::create('other_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->string('month');
            $table->float('amount')->default(0);
            $table->string('currency');
            $table->foreignIdFor(CategoryExpense::class)->constrained();
            $table->foreignIdFor(OtherSourceExpense::class)->constrained();
            $table->foreignIdFor(SchoolYear::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('other_expenses');
    }
};
