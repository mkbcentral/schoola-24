<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'name',
        'url',
        'icon',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Le module parent
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Obtenir l'icône avec valeur par défaut
     */
    public function getIconAttribute($value): string
    {
        return $value ?? 'fas fa-circle';
    }
}
