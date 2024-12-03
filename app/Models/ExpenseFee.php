<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseFee extends Model
{
    use HasFactory;
    protected $fillable = [
        'description',
        'month',
        'amount',
        'currency',
        'category_expense_id',
        'category_fee_id',
        'school_year_id',
        'created_at'
    ];
    /**
     * Get the CategoryExpense that owns the ExpenseFee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categoryExpense(): BelongsTo
    {
        return $this->belongsTo(CategoryExpense::class, 'category_expense_id');
    }

    /**
     * Get the categoryFee that owns the ExpenseFee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categoryFee(): BelongsTo
    {
        return $this->belongsTo(CategoryFee::class, 'category_fee_id',);
    }

    /**
     * Get the scoolYear that owns the ExpenseFee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    /**
     * Summary of scopeFilter
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return Builder
     */
    public function scopeFilter(Builder $query, array $filters)
    {
        return $query
            ->join('category_expenses', 'category_expenses.id', 'expense_fees.category_expense_id')
            ->join('schools', 'schools.id', 'category_expenses.school_id')
            ->when(
                $filters['date'],
                function ($query, $val) {
                    return $query->whereDate('expense_fees.created_at', $val);
                }
            )->when(
                $filters['month'],
                function ($query, $val) {
                    return $query->where('expense_fees.month', $val);
                }
            )->when(
                $filters['categoryFeeId'],
                function ($query, $val) {
                    return $query->where('expense_fees.category_fee_id', $val);
                }
            )->when(
                $filters['categoryExpenseId'],
                function ($query, $val) {
                    return $query->where('expense_fees.category_expense_id', $val);
                }
            )->when(
                $filters['currency'],
                function ($query, $val) {
                    return $query->where('expense_fees.currency', $val);
                }
            )->where('category_expenses.school_id', School::DEFAULT_SCHOOL_ID())
            ->where('expense_fees.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->select('expense_fees.*');
    }
}
