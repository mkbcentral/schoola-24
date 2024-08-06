<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryRegistrationFee extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_old', 'school_id'];

    /**
     * Get all of the registrationFees for the CategoryRegistrationFee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function RegistrationFees(): HasMany
    {
        return $this->hasMany(RegistrationFee::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_old' => 'boolean',
        ];
    }
}
