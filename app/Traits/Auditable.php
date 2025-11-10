<?php

namespace App\Traits;

use App\Models\ArticleAudit;
use App\Models\School;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    /**
     * Boot le trait
     */
    protected static function bootAuditable()
    {
        // Audit lors de la création
        static::created(function ($model) {
            $model->auditAction('created', null, $model->getAuditableAttributes());
        });

        // Audit lors de la mise à jour
        static::updated(function ($model) {
            $changes = $model->getChanges();
            if (!empty($changes)) {
                $model->auditAction('updated', $model->getOriginal(), $changes);
            }
        });

        // Audit lors de la suppression
        static::deleted(function ($model) {
            $model->auditAction('deleted', $model->getAuditableAttributes(), null);
        });
    }

    /**
     * Enregistrer une action d'audit
     */
    public function auditAction(string $action, ?array $oldValues = null, ?array $newValues = null)
    {
        ArticleAudit::create([
            'article_id' => $this->id,
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
        ];

        return array_diff_key($attributes, array_flip($excludeFields));
    }

    /**
     * Filtrer les attributs auditables
     */
    private function filterAuditableAttributes(?array $attributes): ?array
    {
        if (!$attributes) {
            return null;
        }

        $excludeFields = [
            'id',
            'created_at',
            'updated_at',
            'deleted_at',
            'school_id',
            'school_year_id',
        ];

        return array_diff_key($attributes, array_flip($excludeFields));
    }

    /**
     * Audit manuel pour ajustement de stock
     */
    public function auditStockAdjustment(int $oldStock, int $newStock, string $reason = '')
    {
        $this->auditAction('stock_adjusted', [
            'stock' => $oldStock,
            'reason' => $reason,
        ], [
            'stock' => $newStock,
            'reason' => $reason,
        ]);
    }

    /**
     * Relation avec les audits
     */
    public function audits()
    {
        return $this->hasMany(ArticleAudit::class, 'article_id');
    }
}
