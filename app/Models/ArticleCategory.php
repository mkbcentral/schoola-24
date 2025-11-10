<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArticleCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'color',
        'school_id',
    ];

    /**
     * Relation avec les articles
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'category_id');
    }

    /**
     * Obtenir le nombre d'articles dans cette catégorie
     */
    public function getArticlesCountAttribute(): int
    {
        return $this->articles()->count();
    }
}
