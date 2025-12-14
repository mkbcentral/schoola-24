<?php

namespace App\Models;

use App\Domain\Features\Payment\PaymentFeature;
use Illuminate\Database\Eloquent\Builder;
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
        'is_accessory',
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
     */
    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    /**
     * Get all of the comments for the CategoryFee
     */
    public function scolarFees(): HasMany
    {
        return $this->hasMany(ScolarFee::class);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->where('name', 'like', '%' . $filters['search'] . '%');
    }
}
