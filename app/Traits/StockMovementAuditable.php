<?php

namespace App\Traits;

use App\Models\ArticleAudit;
use App\Models\School;
use Illuminate\Support\Facades\Auth;

trait StockMovementAuditable
{
    /**
     * Boot le trait
     */
    protected static function bootStockMovementAuditable()
    {
        // Audit lors de la création
        static::created(function ($model) {
            $model->auditStockMovementAction('movement_created', null, $model->getAuditableAttributes());
        });

        // Audit lors de la mise à jour
        static::updated(function ($model) {
            $changes = $model->getChanges();
            if (! empty($changes)) {
                // Si c'est une clôture
                if (isset($changes['is_closed']) && $changes['is_closed'] == true) {
                    $model->auditStockMovementAction('movement_closed', $model->getOriginal(), $changes);
                } else {
                    $model->auditStockMovementAction('movement_updated', $model->getOriginal(), $changes);
                }
            }
        });

        // Audit lors de la suppression
        static::deleted(function ($model) {
            $model->auditStockMovementAction('movement_deleted', $model->getAuditableAttributes(), null);
        });
    }

    /**
     * Enregistrer une action d'audit pour un mouvement de stock
     */
    public function auditStockMovementAction(string $action, ?array $oldValues = null, ?array $newValues = null)
    {
        ArticleAudit::create([
            'article_id' => $this->article_id,
            'stock_movement_id' => $this->id,
            'action' => $action,
            'old_values' => $this->filterAuditableAttributes($oldValues),
            'new_values' => $this->filterAuditableAttributes($newValues),
            'user_id' => Auth::id() ?? 1,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'school_id' => School::DEFAULT_SCHOOL_ID(),
        ]);
    }

    /**
     * Obtenir les attributs auditables
     */
    public function getAuditableAttributes(): array
    {
        $attributes = $this->getAttributes();

        // Exclure les champs techniques
        $excludeFields = [
            'id',
            'created_at',
            'updated_at',
            'deleted_at',
            'school_id',
            'school_year_id',
            'user_id',
        ];

        return array_diff_key($attributes, array_flip($excludeFields));
    }

    /**
     * Filtrer les attributs auditables
     */
    private function filterAuditableAttributes(?array $attributes): ?array
    {
        if (! $attributes) {
            return null;
        }

        $excludeFields = [
            'id',
            'created_at',
            'updated_at',
            'deleted_at',
            'school_id',
            'school_year_id',
            'user_id',
        ];

        return array_diff_key($attributes, array_flip($excludeFields));
    }

    /**
     * Relation avec les audits
     */
    public function audits()
    {
        return $this->hasMany(ArticleAudit::class, 'stock_movement_id');
    }
}
