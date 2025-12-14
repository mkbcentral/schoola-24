<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rate extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'school_id', 'is_changed'];

    /**
     * Get all of the registrations for the Rate
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Get all of the payments for the Rate
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public static function DEFAULT_RATE(): int
    {
        return Rate::query()->where('is_changed', false)->first()->amount;
    }

    public static function DEFAULT_RATE_ID(): int
    {
        return Rate::query()->where('is_changed', false)->first()->id;
    }
}
