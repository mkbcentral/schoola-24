<?php

namespace App\Models;

use App\Domain\Features\Finance\SalaryFeature;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'month',
        'school_id',
        'school_year_id',
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
     * Get all of the salaryDetails for the Salary
     */
    public function salaryDetails(): HasMany
    {
        return $this->hasMany(SalaryDetail::class);
    }

    public function getAmount(string $currency): int|float
    {
        return SalaryFeature::getDetailAmountTotal($this->id, $currency);
    }

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['date'], function ($query, $val) {
            return $query->whereDate('created_at', $val);
        })
            ->when($filters['month'], function ($query, $val) {
                return $query->where('month', $val);
            })
            ->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID());
    }
}
