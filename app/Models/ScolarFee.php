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
        'is_changed',
    ];

    /**
     * Get the currency that owns the ScolarFee
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    /**
     * Get the categoryFee that owns the ScolarFee
     */
    public function categoryFee(): BelongsTo
    {
        return $this->belongsTo(CategoryFee::class, 'category_fee_id');
    }

    /**
     * Get the classRoom that owns the ScolarFee
     */
    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class, 'class_room_id');
    }

    /**
     * Get all of the payments for the ScolarFee
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function scopeFilter($query, array $filters)
    {
        return $query->join('category_fees', 'category_fees.id', 'scolar_fees.category_fee_id')
            ->join('class_rooms', 'class_rooms.id', 'scolar_fees.class_room_id')
            ->join('options', 'class_rooms.option_id', 'options.id')
            ->where('category_fee_id', $filters['category_fee_id'])
            ->when(
                $filters['option_id'],
                function ($query, $f) {
                    return $query->where('class_rooms.option_id', $f);
                }
            )
            ->when(
                $filters['class_room_id'],
                function ($query, $f) {
                    return $query->where('scolar_fees.class_room_id', $f);
                }
            )
            ->where('category_fees.school_id', School::DEFAULT_SCHOOL_ID())
            ->select('scolar_fees.*');
    }
}
