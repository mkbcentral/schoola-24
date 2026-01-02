<?php

namespace App\Helpers;

use App\Enums\ModuleType;
use App\Enums\SubscriptionStatus;
use App\Models\Module;
use App\Models\School;
use App\Models\SchoolModule;
use Carbon\Carbon;

class SubscriptionHelper
{
    /**
     * Formater un code de module
     */
    public static function formatModuleCode(string $code): string
    {
        return strtolower(str_replace(' ', '_', $code));
    }

    /**
     * Obtenir l'icône d'un module
     */
    public static function getModuleIcon(string $moduleCode): string
    {
        return ModuleType::getIcon($moduleCode);
    }

    /**
     * Obtenir la couleur d'un module
     */
    public static function getModuleColor(string $moduleCode): string
    {
        return ModuleType::getColor($moduleCode);
    }

    /**
     * Formater une date d'expiration
     */
    public static function formatExpirationDate(?Carbon $date): string
    {
        if (!$date) {
            return 'Illimitée';
        }

        if ($date->isPast()) {
            return 'Expiré le ' . $date->format('d/m/Y');
        }

        $daysRemaining = now()->diffInDays($date, false);

        if ($daysRemaining <= 0) {
            return 'Expiré';
        }

        if ($daysRemaining <= 7) {
            return "Expire dans {$daysRemaining} jour(s)";
        }

        return 'Expire le ' . $date->format('d/m/Y');
    }

    /**
     * Obtenir le badge de statut HTML
     */
    public static function getStatusBadge(string $status): string
    {
        $class = SubscriptionStatus::getBadgeClass($status);
        $label = SubscriptionStatus::getLabel($status);

        return "<span class='badge {$class}'>{$label}</span>";
    }
    public static function canSubscribe(School $school, Module $module): bool
    {
        // Vérifier si le module est actif
        if (!$module->is_active) {
            return false;
        }

        // Vérifier si l'école n'a pas déjà une souscription active
        $existingSubscription = SchoolModule::where('school_id', $school->id)
            ->where('module_id', $module->id)
            ->whereIn('status', [SubscriptionStatus::ACTIVE, SubscriptionStatus::TRIAL])
            ->first();

        return !$existingSubscription;
    }

    /**
     * Calculer le pourcentage de la période restante
     */
    public static function getExpirationPercentage(SchoolModule $subscription): int
    {
        if (!$subscription->started_at || !$subscription->expires_at) {
            return 100;
        }

        $totalDays = $subscription->started_at->diffInDays($subscription->expires_at);
        $daysRemaining = now()->diffInDays($subscription->expires_at, false);

        if ($daysRemaining <= 0) {
            return 0;
        }

        if ($totalDays <= 0) {
            return 100;
        }

        return (int) (($daysRemaining / $totalDays) * 100);
    }

    /**
     * Obtenir la classe CSS pour le badge d'expiration
     */
    public static function getExpirationBadgeClass(int $daysRemaining): string
    {
        if ($daysRemaining <= 0) {
            return 'badge-danger';
        }

        if ($daysRemaining <= 7) {
            return 'badge-warning';
        }

        if ($daysRemaining <= 30) {
            return 'badge-info';
        }

        return 'badge-success';
    }

    /**
     * Formater un prix avec la devise
     */
    public static function formatPrice(float $price, string $currency = 'FC'): string
    {
        return number_format($price, 0, ',', ' ') . ' ' . $currency;
    }

    /**
     * Obtenir la description d'une période
     */
    public static function getPeriodDescription(string $period): string
    {
        return match ($period) {
            'monthly' => 'Mensuel',
            'quarterly' => 'Trimestriel',
            'semi_annual' => 'Semestriel',
            'annual' => 'Annuel',
            default => ucfirst($period),
        };
    }

    /**
     * Vérifier si une souscription nécessite une attention
     */
    public static function needsAttention(SchoolModule $subscription): bool
    {
        // Expire dans moins de 7 jours
        if ($subscription->daysRemaining() <= 7 && $subscription->daysRemaining() > 0) {
            return true;
        }

        // Est expiré
        if ($subscription->isExpired()) {
            return true;
        }

        // Est suspendu
        if ($subscription->status === SubscriptionStatus::SUSPENDED) {
            return true;
        }

        return false;
    }

    /**
     * Obtenir le message d'attention pour une souscription
     */
    public static function getAttentionMessage(SchoolModule $subscription): ?string
    {
        if ($subscription->isExpired()) {
            return "Ce module a expiré. Veuillez renouveler votre souscription.";
        }

        $daysRemaining = $subscription->daysRemaining();
        if ($daysRemaining <= 7 && $daysRemaining > 0) {
            return "Ce module expire dans {$daysRemaining} jour(s). Pensez à renouveler.";
        }

        if ($subscription->status === SubscriptionStatus::SUSPENDED) {
            return "Ce module est actuellement suspendu.";
        }

        return null;
    }

    /**
     * Générer un résumé des modules d'une école
     */
    public static function getSchoolModulesSummary(School $school): array
    {
        $subscriptions = $school->schoolModules;

        $active = $subscriptions->where('status', SubscriptionStatus::ACTIVE)->count();
        $trial = $subscriptions->where('status', SubscriptionStatus::TRIAL)->count();
        $expired = $subscriptions->where('status', SubscriptionStatus::EXPIRED)->count();
        $expiringSoon = $subscriptions->filter(function ($sub) {
            return $sub->daysRemaining() > 0 && $sub->daysRemaining() <= 7;
        })->count();

        return [
            'total' => $subscriptions->count(),
            'active' => $active,
            'trial' => $trial,
            'expired' => $expired,
            'expiring_soon' => $expiringSoon,
            'suspended' => $subscriptions->where('status', SubscriptionStatus::SUSPENDED)->count(),
            'needs_attention' => $expired + $expiringSoon,
        ];
    }

    /**
     * Obtenir les modules recommandés pour une école
     */
    public static function getRecommendedModules(School $school, int $limit = 3)
    {
        // Obtenir les modules non souscrits
        $subscribedModuleIds = $school->schoolModules->pluck('module_id')->toArray();

        return Module::active()
            ->whereNotIn('id', $subscribedModuleIds)
            ->orderBy('sort_order')
            ->limit($limit)
            ->get();
    }

    /**
     * Vérifier si c'est le bon moment pour proposer un renouvellement
     */
    public static function shouldProposeRenewal(SchoolModule $subscription): bool
    {
        $daysRemaining = $subscription->daysRemaining();

        // Proposer le renouvellement 14 jours avant l'expiration
        return $daysRemaining > 0 && $daysRemaining <= 14 && $subscription->hasAccess();
    }
}
