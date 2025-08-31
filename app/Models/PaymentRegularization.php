<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentRegularization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'month',
        'category_fee_id',
        'class_room_id',
        'created_at',
        'school_id',
        'school_year_id'
    ];

    /**
     * Get the classRoom that owns the PaymentRegularization
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class, 'class_room_id');
    }

    /**
     * Get the categoryFee that owns the PaymentRegularization
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categoryFee(): BelongsTo
    {
        return $this->belongsTo(CategoryFee::class, 'category_fee_id');
    }

    public function scopeFilter(Builder $query, array $filter): Builder
    {
        return $query->join('class_rooms', 'class_rooms.id', 'payment_regularizations.class_room_id')
            ->join('options', 'options.id', 'class_rooms.option_id')
            ->when(
                $filter['date'],
                function ($query, $key) {
                    return $query->whereDate('payment_regularizations.created_at', $key);
                }
            )
            ->when(
                $filter['month'],
                function ($query, $key) {
                    return $query->where('payment_regularizations.month', $key);
                }
            )
            ->when(
                $filter['categoryFeeId'],
                function ($query, $key) {
                    return $query->where('payment_regularizations.category_fee_id', $key);
                }
            )->when(
                $filter['optionId'],
                function ($query, $key) {
                    return $query->where('options.id', $key);
                }
            )
            ->when(
                $filter['classRoomId'],
                function ($query, $key) {
                    return $query->where('payment_regularizations.class_room_id', $key);
                }
            )
            ->when(
                $filter['q'],
                function ($query, $key) {
                    return $query->where('payment_regularizations.name', 'like', '%' . $key . '%');
                }
            )
            ->where('payment_regularizations.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->with(['classRoom', 'categoryFee'])
            ->select('payment_regularizations.*');
    }
}
