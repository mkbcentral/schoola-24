<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OtherSourceExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'school_id',
    ];

    /**
     * Get the school that owns the CategoryExpense
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    /**
     * Get all of the otherExpenses for the OtherSourceExpense
     */
    public function otherExpenses(): HasMany
    {
        return $this->hasMany(OtherExpense::class);
    }
}
