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
        'salary_id'
    ];

    /**
     * Get the salary that owns the SalaryDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function salary(): BelongsTo
    {
        return $this->belongsTo(Salary::class, 'salary_id');
    }
}
