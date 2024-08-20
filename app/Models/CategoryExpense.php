<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryExpense extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'school_id'
    ];
    /**
     * Get the school that owns the CategoryExpense
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }
    /**
     * Get all of the expenseFee for the CategoryExpense
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expenseFee(): HasMany
    {
        return $this->hasMany(ExpenseFee::class);
    }
}
