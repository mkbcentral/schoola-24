<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OtherExpense extends Model
{
    use HasFactory;
    protected $fillable = [
        'description',
        'month',
        'amount',
        'currency',
        'category_expense_id',
        'other_source_expense_id',
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
     * Get the otherSourceExpense that owns the ExpenseFee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function otherSourceExpense(): BelongsTo
    {
        return $this->belongsTo(OtherSourceExpense::class, 'other_source_expense_id',);
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

    public function scopeFilter(Builder $query, array $filters)
    {
        return $query
            ->join('category_expenses', 'category_expenses.id', 'other_expenses.category_expense_id')
            ->join('schools', 'schools.id', 'category_expenses.school_id')
            ->when(
                $filters['date'],
                function ($query, $val) {
                    return $query->whereDate('other_expenses.created_at', $val);
                }
            )->when(
                $filters['month'],
                function ($query, $val) {
                    return $query->where('other_expenses.month', $val);
                }
            )->when(
                $filters['otherSourceExpenseId'],
                function ($query, $val) {
                    return $query->where('other_expenses.other_source_expense_id', $val);
                }
            )->when(
                $filters['categoryExenseId'],
                function ($query, $val) {
                    return $query->where('other_expenses.category_expense_id', $val);
                }
            )->when(
                $filters['currency'],
                function ($query, $val) {
                    return $query->where('other_expenses.currency', $val);
                }
            )->where('category_expenses.school_id', School::DEFAULT_SCHOOL_ID())
            ->where('other_expenses.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->select('other_expenses.*');
    }
}
