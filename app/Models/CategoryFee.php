<?php

namespace App\Models;

use App\Domain\Features\Payment\PaymentFeature;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'school_year_id',
        'school_id',
        'is_state_fee',
        'currency',
        'is_paid_in_installment',
        'is_paid_for_registration',
        'is_state_fee',
        'is_for_dash',
        'is_accessory'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_state_fee' => 'boolean',
            'is_paid_in_installment' => 'boolean',
            'is_paid_for_registration' => 'boolean',
            'is_for_dash' => 'boolean',
            'is_accessory' => 'boolean',
        ];
    }


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

    public function getAmountByDate(string $date): string|null
    {
        return PaymentFeature::getTotal(
            $date,
            null,
            $this->id,
            null,
            null,
            null,
            null,
            true,
            null,
            'CDF'
        );
    }
    public function getAmountByMonth(string $month): int|float
    {
        return PaymentFeature::getTotal(
            null,
            $month,
            $this->id,
            null,
            null,
            null,
            null,
            true,
            'CDF'
        );
    }
}
