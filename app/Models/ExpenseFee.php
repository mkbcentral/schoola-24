<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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

    /**
     * Summary of getTotalExpensesByMonth
     * @param int $categoryExpenseId
     * @return \Illuminate\Support\Collection
     */
    public static function getTotalExpensesByMonth(int $categoryFeeId, int $categoryExpenseId): Collection
    {
        return self::join('category_expenses', 'expense_fees.category_expense_id', 'category_expenses.id')
            ->join('category_fees', 'expense_fees.category_fee_id', 'category_fees.id')
            ->select(
                DB::raw('expense_fees.month as month'),
                DB::raw('SUM(CASE WHEN expense_fees.currency = "USD" THEN expense_fees.amount ELSE 0 END) as total_usd'),
                DB::raw('SUM(CASE WHEN expense_fees.currency = "CDF" THEN expense_fees.amount ELSE 0 END) as total_cdf')
            )
            ->groupBy('expense_fees.month')
            ->where('category_fees.id', $categoryFeeId)
            ->where('category_expenses.school_id', School::DEFAULT_SCHOOL_ID())
            ->where('expense_fees.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->when(
                $categoryExpenseId,
                function ($query, $val) {
                    return $query->where('expense_fees.category_expense_id', $val);
                }
            )
            ->orderBy('expense_fees.month', 'asc')
            ->get();
    }




    public static function getExpensesByMonthAndCategory(int $categoryId)
    {
        return self::join(
            'category_fees',
            'expense_fees.category_fee_id',
            '=',
            'category_fees.id'
        )
            ->select(
                DB::raw('expense_fees.month as month'),
                'category_fees.name as category_name',
                DB::raw('SUM(CASE WHEN expense_fees.currency = "CDF" THEN expense_fees.amount / 2850 ELSE expense_fees.amount END) as total_amount')
            )
            ->where("category_fees.id",  $categoryId)
            ->groupBy(DB::raw('expense_fees.month'), 'category_fees.name')
            ->get();
    }




    public static function getTotalExpenses()
    {
        return self::join('category_expenses', 'expense_fees.category_expense_id', 'category_expenses.id')
            ->join('category_fees', 'expense_fees.category_fee_id', 'category_fees.id')
            ->select(
                DB::raw('SUM(CASE WHEN expense_fees.currency = "CDF" THEN expense_fees.amount / 2850 ELSE expense_fees.amount END) as total_usd')
            )
            ->where('category_expenses.school_id', School::DEFAULT_SCHOOL_ID())
            ->where('expense_fees.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->get();
    }

    //Le montant total par jour
    public static function getTotalExpensesByDate(int $categoryFeeId, int $categoryExpenseId, string $date): Collection
    {
        return self::join('category_expenses', 'expense_fees.category_expense_id', 'category_expenses.id')
            ->join('category_fees', 'expense_fees.category_fee_id', 'category_fees.id')
            ->select(
                DB::raw('SUM(CASE WHEN expense_fees.currency = "USD" THEN expense_fees.amount ELSE 0 END) as total_usd'),
                DB::raw('SUM(CASE WHEN expense_fees.currency = "CDF" THEN expense_fees.amount ELSE 0 END) as total_cdf')
            )
            ->where('category_expenses.school_id', School::DEFAULT_SCHOOL_ID())
            ->where('expense_fees.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->whereDate('expense_fees.created_at', $date)
            ->when(
                $categoryFeeId,
                function ($query, $val) {
                    return $query->where('category_fees.id', $val);
                }
            )
            ->when(
                $categoryExpenseId,
                function ($query, $val) {
                    return $query->where('expense_fees.category_expense_id', $val);
                }
            )
            ->get();
    }
}
