<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'school_year_id'
    ];

    /**
     * Get the schoolYear that owns the CategoryFee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }
    /**
     * Get all of the comments for the CategoryFee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scolarFees(): HasMany
    {
        return $this->hasMany(ScolarFee::class);
    }
}
