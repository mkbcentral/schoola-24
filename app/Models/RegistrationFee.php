<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RegistrationFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'option_id',
        'category_registration_fee_id',
        'school_year_id',
        'currency',
    ];

    /**
     * Get the option that owns the RegistrationFee
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class, 'option_id');
    }

    /**
     * Get all of the registrations for the RegistrationFee
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Get the category_registration_fee_id that owns the RegistrationFee
     */
    public function categoryRegistrationFee(): BelongsTo
    {
        return $this->belongsTo(CategoryRegistrationFee::class, 'category_registration_fee_id');
    }
}
