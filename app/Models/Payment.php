<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_number',
        'month',
        'registration_id',
        'scolar_fee_id',
        'rate_id',
        'user_id',
        'is_paid',
        'created_at',
    ];

    /**
     * Get the registration that owns the Payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    /**
     * Get the scolarFee that owns the Payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scolarFee(): BelongsTo
    {
        return $this->belongsTo(ScolarFee::class, 'scolar_fee_id');
    }

    /**
     * Get the rate that owns the Payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rate(): BelongsTo
    {
        return $this->belongsTo(Rate::class, 'rate_id');
    }

    /**
     * Get the user that owns the Payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    /**
     * Summary of scopeFilter
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->join('registrations', 'registrations.id', 'payments.registration_id')
            ->join('students', 'students.id', 'registrations.student_id')
            ->join('class_rooms', 'class_rooms.id', 'registrations.class_room_id')
            ->join('options', 'options.id', 'class_rooms.option_id')
            ->join('sections', 'sections.id', 'options.section_id')
            ->join('scolar_fees', 'scolar_fees.id', 'payments.scolar_fee_id')
            ->join('category_fees', 'category_fees.id', 'scolar_fees.category_fee_id')
            ->when(
                $filters['date'],
                function ($query, $f) {
                    return $query->whereDate('payments.created_at', $f);
                }
            )
            ->when(
                $filters['month'],
                function ($query, $f) {
                    return $query->where('payments.month', $f);
                }
            )
            ->when(
                $filters['categoryFeeId'],
                function ($query, $f) {
                    return $query->where('category_fees.id', $f);
                }
            )
            ->when(
                $filters['feeId'],
                function ($query, $f) {
                    return $query->where('payments.scolar_fee_id', $f);
                }
            )
            ->when(
                $filters['sectionId'],
                function ($query, $f) {
                    return $query->where('sections.id', $f);
                }
            )
            ->when(
                $filters['optionId'],
                function ($query, $f) {
                    return $query->where('options.id', $f);
                }
            )
            ->when(
                $filters['classRoomId'],
                function ($query, $f) {
                    return $query->where('registrations.class_room_id', $f);
                }
            )
            ->when(
                $filters['isPaid'],
                function ($query, $f) {
                    return $query->where('payments.is_paid', $f);
                }
            )
            ->when(
                $filters['key_to_search'],
                function ($query, $f) {
                    return $query->where('students.name', 'like', '%' . $f . '%');
                }
            )
            ->with([
                'registration',
                'scolarFee',
                'rate'
            ])
            ->select('payments.*');
    }

    /**
     * Retourner le montant en CDF
     * @return void
     */
    public function getAmountCDF(): string|null
    {
        if ($this->scolarFee->currency == "USD") {
            return $this->scolarFee->amount * $this->rate->amount . ' Fc';
        } else {
            return $this->scolarFee->amount . ' Fc';
        }
    }
    /**
     * Retourner le montant en USD
     * @return void
     */
    public function getAmountUSD(): string|null
    {
        if ($this->scolarFee->currency == "CDF") {
            return $this->scolarFee->amount / $this->rate->amount . ' $';
        } else {
            return $this->scolarFee->amount . ' $';
        }
    }
}
