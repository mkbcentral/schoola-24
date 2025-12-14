<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    use Auditable;

    protected $fillable = [
        'name',
        'reference',
        'unit',
        'description',
        'category_id',
        'stock_min',
        'school_id',
        'school_year_id',
    ];

    public function stockMovements(): HasMany
    {
        return $this->hasMany(ArticleStockMovement::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    public function getStockAttribute(): int
    {
        // Calcule le stock courant uniquement sur les mouvements clôturés
        $in = $this->stockMovements()->where('type', 'in')->where('is_closed', true)->sum('quantity');
        $out = $this->stockMovements()->where('type', 'out')->where('is_closed', true)->sum('quantity');

        return $in - $out;
    }

    /**
     * Vérifier si le stock est en alerte (inférieur au minimum)
     */
    public function getIsLowStockAttribute(): bool
    {
        if (! $this->stock_min || $this->stock_min == 0) {
            return false;
        }

        return $this->stock <= $this->stock_min;
    }

    /**
     * Vérifier si le stock est critique (stock à 0 ou négatif)
     */
    public function getIsCriticalStockAttribute(): bool
    {
        return $this->stock <= 5;
    }

    /**
     * Scope pour récupérer les articles avec stock faible
     */
    public function scopeLowStock($query)
    {
        return $query->whereNotNull('stock_min')
            ->where('stock_min', '>', 5)
            ->get()
            ->filter(function ($article) {
                return $article->stock <= $article->stock_min;
            });
    }
}
