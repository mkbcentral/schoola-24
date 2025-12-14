<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalaryDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'amount',
        'currency',
        'category_salary_id',
        'salary_id',
    ];

    /**
     * Get the salary that owns the SalaryDetail
     */
    public function salary(): BelongsTo
    {
        return $this->belongsTo(Salary::class, 'salary_id');
    }

    /**
     * Get the categorySalary that owns the SalaryDetail
     */
    public function categorySalary(): BelongsTo
    {
        return $this->belongsTo(CategorySalary::class, 'category_salary_id');
    }
}
