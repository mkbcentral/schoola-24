<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'expected_quantity',
        'actual_quantity',
        'difference',
        'status',
        'inventory_date',
        'note',
        'user_id',
        'school_id',
        'school_year_id',
    ];

    protected $casts = [
        'inventory_date' => 'date',
        'expected_quantity' => 'integer',
        'actual_quantity' => 'integer',
        'difference' => 'integer',
    ];

    /**
     * Relation avec l'article
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec l'école
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Relation avec l'année scolaire
     */
    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    /**
     * Accesseur pour le badge du statut
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'conforme' => 'success',
            'excedent' => 'info',
            'manquant' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Accesseur pour le libellé du statut
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'conforme' => 'Conforme',
            'excedent' => 'Excédent',
            'manquant' => 'Manquant',
            default => 'Inconnu',
        };
    }

    /**
     * Accesseur pour l'icône du statut
     */
    public function getStatusIconAttribute(): string
    {
        return match ($this->status) {
            'conforme' => 'bi-check-circle-fill',
            'excedent' => 'bi-arrow-up-circle-fill',
            'manquant' => 'bi-exclamation-circle-fill',
            default => 'bi-question-circle-fill',
        };
    }

    /**
     * Scope pour filtrer par article
     */
    public function scopeForArticle($query, int $articleId)
    {
        return $query->where('article_id', $articleId);
    }

    /**
     * Scope pour filtrer par statut
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope pour les inventaires récents
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('inventory_date', '>=', now()->subDays($days));
    }
}
