<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Collection;
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
                $filters['categoryExpenseId'],
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

    public static function getTotalExpenses()
    {
        return self::join('category_expenses', 'other_expenses.category_expense_id', 'category_expenses.id')
            ->select(
                DB::raw('SUM(CASE WHEN other_expenses.currency = "CDF" THEN other_expenses.amount / 2850 ELSE other_expenses.amount END) as total_usd')
            )
            ->where('category_expenses.school_id', School::DEFAULT_SCHOOL_ID())
            ->where('other_expenses.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->get();
    }

    public static function getTotalExpensesByDate(int $categoryExpenseId, int $otherSourceExpenseId, string $date): Collection
    {
        return self::join('category_expenses', 'other_expenses.category_expense_id', 'category_expenses.id')
            ->join('other_source_expenses', 'other_expenses.other_source_expense_id', 'other_source_expenses.id')
            ->select(
                DB::raw('SUM(CASE WHEN other_expenses.currency = "USD" THEN other_expenses.amount ELSE 0 END) as total_usd'),
                DB::raw('SUM(CASE WHEN other_expenses.currency = "CDF" THEN other_expenses.amount ELSE 0 END) as total_cdf')
            )
            ->where('category_expenses.school_id', School::DEFAULT_SCHOOL_ID())
            ->where('other_expenses.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->whereDate('other_expenses.created_at', $date)
            ->when(
                $categoryExpenseId,
                function ($query, $val) {
                    return $query->where('category_expenses.id', $val);
                }
            )
            ->when(
                $otherSourceExpenseId,
                function ($query, $val) {
                    return $query->where('other_source_expenses.id', $val);
                }
            )
            ->get();
    }

    public static function getTotalExpensesByMonth(int $categoryExpenseId, int $otherSourceExpenseId): Collection
    {
        return self::join('category_expenses', 'other_expenses.category_expense_id', 'category_expenses.id')
            ->join('other_source_expenses', 'other_expenses.other_source_expense_id', 'other_source_expenses.id')
            ->select(
                DB::raw('other_expenses.month as month'),
                DB::raw('SUM(CASE WHEN other_expenses.currency = "USD" THEN other_expenses.amount ELSE 0 END) as total_usd'),
                DB::raw('SUM(CASE WHEN other_expenses.currency = "CDF" THEN other_expenses.amount ELSE 0 END) as total_cdf')
            )
            ->groupBy('other_expenses.month')
            ->where('category_expenses.school_id', School::DEFAULT_SCHOOL_ID())
            ->where('other_expenses.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->when(
                $categoryExpenseId,
                function ($query, $val) {
                    return $query->where('other_expenses.category_expense_id', $val);
                }
            )
            ->when(
                $otherSourceExpenseId,
                function ($query, $val) {
                    return $query->where('other_expenses.other_source_expense_id', $val);
                }
            )
            ->orderBy('other_expenses.month', 'asc')
            ->get();
    }
}
