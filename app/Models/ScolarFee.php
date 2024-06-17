<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScolarFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'category_fee_id',
        'section_id',
        'currency_id'
    ];

    /**
     * Get the currency that owns the ScolarFee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    /**
     * Get the categoryFee that owns the ScolarFee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categoryFee(): BelongsTo
    {
        return $this->belongsTo(CategoryFee::class, 'category_fee_id');
    }

    /**
     * Get the section that owns the ScolarFee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    /**
     * Get all of the payments for the ScolarFee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
