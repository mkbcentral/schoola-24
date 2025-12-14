<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'stock_movement_id',
        'action',
        'old_values',
        'new_values',
        'user_id',
        'ip_address',
        'user_agent',
        'school_id',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
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
     * Relation avec le mouvement de stock
     */
    public function stockMovement(): BelongsTo
    {
        return $this->belongsTo(ArticleStockMovement::class, 'stock_movement_id');
    }

    /**
     * Accesseur pour le badge de l'action
     */
    public function getActionBadgeAttribute(): string
    {
        return match ($this->action) {
            'created' => 'success',
            'updated' => 'primary',
            'deleted' => 'danger',
            'stock_adjusted' => 'warning',
            'movement_created' => 'success',
            'movement_updated' => 'info',
            'movement_closed' => 'dark',
            'movement_deleted' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Accesseur pour le libellé de l'action
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'created' => 'Création Article',
            'updated' => 'Modification Article',
            'deleted' => 'Suppression Article',
            'stock_adjusted' => 'Ajustement Stock',
            'movement_created' => 'Mouvement Créé',
            'movement_updated' => 'Mouvement Modifié',
            'movement_closed' => 'Mouvement Clôturé',
            'movement_deleted' => 'Mouvement Supprimé',
            default => 'Inconnu',
        };
    }

    /**
     * Accesseur pour l'icône de l'action
     */
    public function getActionIconAttribute(): string
    {
        return match ($this->action) {
            'created' => 'bi-plus-circle-fill',
            'updated' => 'bi-pencil-fill',
            'deleted' => 'bi-trash-fill',
            'stock_adjusted' => 'bi-arrow-repeat',
            'movement_created' => 'bi-arrow-down-circle-fill',
            'movement_updated' => 'bi-pencil-square',
            'movement_closed' => 'bi-lock-fill',
            'movement_deleted' => 'bi-x-circle-fill',
            default => 'bi-question-circle-fill',
        };
    }

    /**
     * Obtenir les changements lisibles
     */
    public function getChangesAttribute(): array
    {
        if (! $this->old_values || ! $this->new_values) {
            return [];
        }

        $changes = [];
        $fields = array_unique(array_merge(
            array_keys($this->old_values),
            array_keys($this->new_values)
        ));

        foreach ($fields as $field) {
            $oldValue = $this->old_values[$field] ?? null;
            $newValue = $this->new_values[$field] ?? null;

            if ($oldValue !== $newValue) {
                $changes[$field] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                    'label' => $this->getFieldLabel($field),
                ];
            }
        }

        return $changes;
    }

    /**
     * Obtenir le libellé d'un champ
     */
    private function getFieldLabel(string $field): string
    {
        return match ($field) {
            'name' => 'Nom',
            'reference' => 'Référence',
            'description' => 'Description',
            'unit' => 'Unité',
            'stock' => 'Stock',
            'stock_min' => 'Stock Minimum',
            'price' => 'Prix',
            'category_id' => 'Catégorie',
            'type' => 'Type de mouvement',
            'quantity' => 'Quantité',
            'movement_date' => 'Date du mouvement',
            'note' => 'Note',
            'is_closed' => 'Statut',
            'closed_date' => 'Date de clôture',
            default => ucfirst($field),
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
     * Scope pour filtrer par action
     */
    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope pour les audits récents
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
