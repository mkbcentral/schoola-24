<?php

namespace App\Models;

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
        'school_year_id'
    ];

    /**
     * Get the school that owns the Salary
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    /**
     * Get the schoolYear that owns the Salary
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }
    /**
     * Get all of the salaryDetails for the Salary
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function salaryDetails(): HasMany
    {
        return $this->hasMany(SalaryDetail::class);
    }
}
