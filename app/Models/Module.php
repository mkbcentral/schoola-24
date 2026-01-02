<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'price',
        'description',
        'icon',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Les fonctionnalités du module
     */
    public function features(): HasMany
    {
        return $this->hasMany(ModuleFeature::class)->orderBy('sort_order');
    }

    /**
     * Les écoles qui ont ce module
     */
    public function schools(): BelongsToMany
    {
        return $this->belongsToMany(School::class, 'school_module')
            ->withPivot('id', 'assigned_at', 'assigned_by')
            ->withTimestamps();
    }

    /**
     * Scope pour les modules actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Obtenir le prix formaté
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', ' ') . ' FC';
    }

    /**
     * Obtenir le nombre d'écoles utilisant ce module
     */
    public function getSchoolsCountAttribute(): int
    {
        return $this->schools()->count();
    }

    /**
     * Obtenir le nombre de fonctionnalités
     */
    public function getFeaturesCountAttribute(): int
    {
        return $this->features()->count();
    }
}
