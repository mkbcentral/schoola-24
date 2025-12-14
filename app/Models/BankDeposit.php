<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankDeposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'amount',
        'month',
        'currency',
        'school_id',
        'school_year_id',
        'category_fee_id',
        'created_at',
    ];

    /**
     * Get the school that owns the Salary
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    /**
     * Get the schoolYear that owns the Salary
     */
    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    /**
     * Get the categoryFee that owns the BankDeposit
     */
    public function categoryFee(): BelongsTo
    {
        return $this->belongsTo(CategoryFee::class, 'category_fee_id');
    }

    /**
     * Summary of scopeFilter
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->when(
            $filters['date'],
            function ($query, $value) {
                return $query->whereDate('created_at', $value);
            }
        )->when(
            $filters['month'],
            function ($query, $value) {
                return $query->where('month', $value);
            }
        )->when(
            $filters['currency'],
            function ($query, $value) {
                return $query->where('currency', $value);
            }
        )->with([
            'categoryFee',
        ])->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID());
    }
}
