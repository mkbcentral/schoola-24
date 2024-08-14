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
        'class_room_id',
        'is_changed'
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
     * Get the classRoom that owns the ScolarFee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class, 'class_room_id');
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
