<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    protected $fillable = [
        'name',
        'reference',
        'unit',
        'description',
        'school_id',
        'school_year_id',
    ];

    public function stockMovements(): HasMany
    {
        return $this->hasMany(ArticleStockMovement::class);
    }

    public function getStockAttribute(): int
    {
        // Calcule le stock courant uniquement sur les mouvements clôturés
        $in = $this->stockMovements()->where('type', 'in')->where('is_closed', true)->sum('quantity');
        $out = $this->stockMovements()->where('type', 'out')->where('is_closed', true)->sum('quantity');
        return $in - $out;
    }
}
