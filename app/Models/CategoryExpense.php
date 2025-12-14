<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'school_id',
    ];

    /**
     * Get the school that owns the CategoryExpense
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    /**
     * Get all of the expenseFee for the CategoryExpense
     */
    public function expenseFee(): HasMany
    {
        return $this->hasMany(ExpenseFee::class);
    }

    /**
     * Get all of the otherExpenses for the CategoryExpense
     */
    public function otherExpenses(): HasMany
    {
        return $this->hasMany(OtherExpense::class);
    }

    // get monthly amounts for the category
    /**
     * Get the total amount for this category for a specific month.
     *
     * @param  string  $month  Format: 'YYYY-MM'
     */
    public function getMonthlyAmount($month): float|int
    {
        return $this->expenseFee()
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->where('month', $month)
            ->sum('amount');
    }
}
