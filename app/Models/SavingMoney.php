<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavingMoney extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'month',
        'currency',
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
        );
    }
}
