<?php

namespace App\Models;

use App\Domain\Features\Registration\RegistrationFeature;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'school_id',
    ];

    /**
     * Get the school that owns the Section
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(User::class, 'school_id');
    }

    /**
     * Get all of the options for the Section
     */
    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }

    /**
     * Get all of the registrationFee for the Section
     */
    public function registrationFee(): HasMany
    {
        return $this->hasMany(RegistrationFee::class);
    }

    /**
     * Summary of getRegistrationCountForCurrentSchoolYear
     */
    public function getRegistrationCountForCurrentSchoolYear(): int|float
    {
        return RegistrationFeature::getCountAll(
            null,
            null,
            $this->id,
            null,
            null,
            null,
        );
    }
}
