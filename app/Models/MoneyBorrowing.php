<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MoneyBorrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'month',
        'amount',
        'currency',
        'school_id',
        'school_year_id',
        'created_at'
    ];

    /**
     * Get the school that owns the MoneyBorrowing
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function scopeFilter($query, array $filters )
    {
        return $query->when($filters['date'], function ($query, $val) {
            return $query->whereDate('created_at', $val);
        })
            ->when($filters['month'], function ($query, $val) {
                return $query->where('month', $val);
            })
            ->when($filters['currency'], function ($query, $val) {
                return $query->where('currency', $val);
            })
            ->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID());
    }
}
