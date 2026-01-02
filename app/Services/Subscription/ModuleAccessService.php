<?php

namespace App\Services\Subscription;

use App\Enums\SubscriptionStatus;
use App\Models\Module;
use App\Models\School;
use App\Models\SchoolModule;
use Illuminate\Support\Facades\Cache;

class ModuleAccessService
{
    /**
     * Vérifier si une école a accès à un module
     */
    public function canAccess(School $school, string $moduleCode): bool
    {
        // Utiliser le cache pour améliorer les performances
        $cacheKey = "school_{$school->id}_module_{$moduleCode}_access";

        return Cache::remember($cacheKey, 300, function () use ($school, $moduleCode) {
            $subscription = $this->getSubscription($school, $moduleCode);

            if (!$subscription) {
                return false;
            }

            return $subscription->hasAccess();
        });
    }

    /**
     * Vérifier si une école a accès à une fonctionnalité spécifique d'un module
     */
    public function hasFeature(School $school, string $moduleCode, string $feature): bool
    {
        if (!$this->canAccess($school, $moduleCode)) {
            return false;
        }

        $module = Module::where('code', $moduleCode)->first();

        if (!$module) {
            return false;
        }

        return $module->hasFeature($feature);
    }

    /**
     * Obtenir la souscription d'une école pour un module
     */
    public function getSubscription(School $school, string $moduleCode): ?SchoolModule
    {
        $module = Module::where('code', $moduleCode)->where('is_active', true)->first();

        if (!$module) {
            return null;
        }

        return SchoolModule::where('school_id', $school->id)
            ->where('module_id', $module->id)
            ->first();
    }

    /**
     * Obtenir les paramètres d'un module pour une école
     */
    public function getModuleSettings(School $school, Module $module): array
    {
        $subscription = SchoolModule::where('school_id', $school->id)
            ->where('module_id', $module->id)
            ->first();

        return $subscription?->settings ?? [];
    }

    /**
     * Définir un paramètre de module pour une école
     */
    public function setModuleSetting(School $school, Module $module, string $key, $value): bool
    {
        $subscription = SchoolModule::where('school_id', $school->id)
            ->where('module_id', $module->id)
            ->first();

        if (!$subscription) {
            return false;
        }

        $subscription->setSetting($key, $value);

        // Invalider le cache
        $this->clearAccessCache($school, $module->code);

        return true;
    }

    /**
     * Obtenir les sections de navigation accessibles pour une école
     */
    public function getAccessibleSections(School $school, Module $module): array
    {
        if (!$this->canAccess($school, $module->code)) {
            return [];
        }

        $sections = $module->getNavigationSections();

        // Filtrer selon les permissions de l'utilisateur
        return collect($sections)->filter(function ($section) {
            if (!isset($section['permission'])) {
                return true;
            }

            return auth()->user()?->can($section['permission']) ?? false;
        })->values()->toArray();
    }

    /**
     * Obtenir tous les modules accessibles par une école
     */
    public function getAccessibleModules(School $school)
    {
        return SchoolModule::with('module')
            ->where('school_id', $school->id)
            ->whereIn('status', [SubscriptionStatus::ACTIVE, SubscriptionStatus::TRIAL])
            ->whereHas('module', function ($query) {
                $query->where('is_active', true);
            })
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->get()
            ->pluck('module');
    }

    /**
     * Vérifier si une école est en période d'essai pour un module
     */
    public function isOnTrial(School $school, string $moduleCode): bool
    {
        $subscription = $this->getSubscription($school, $moduleCode);

        return $subscription && $subscription->isTrial();
    }

    /**
     * Obtenir le nombre de jours restants pour une souscription
     */
    public function getDaysRemaining(School $school, string $moduleCode): int
    {
        $subscription = $this->getSubscription($school, $moduleCode);

        return $subscription ? $subscription->daysRemaining() : 0;
    }

    /**
     * Vérifier si une souscription expire bientôt (dans les 7 jours)
     */
    public function isExpiringSoon(School $school, string $moduleCode, int $days = 7): bool
    {
        $daysRemaining = $this->getDaysRemaining($school, $moduleCode);

        return $daysRemaining > 0 && $daysRemaining <= $days;
    }

    /**
     * Effacer le cache d'accès pour une école et un module
     */
    public function clearAccessCache(School $school, string $moduleCode): void
    {
        $cacheKey = "school_{$school->id}_module_{$moduleCode}_access";
        Cache::forget($cacheKey);
    }

    /**
     * Effacer tout le cache d'accès pour une école
     */
    public function clearSchoolAccessCache(School $school): void
    {
        $modules = Module::all();

        foreach ($modules as $module) {
            $this->clearAccessCache($school, $module->code);
        }
    }

    /**
     * Obtenir les statistiques d'utilisation des modules pour une école
     */
    public function getModuleStats(School $school): array
    {
        $subscriptions = SchoolModule::where('school_id', $school->id)->get();

        return [
            'total' => $subscriptions->count(),
            'active' => $subscriptions->where('status', SubscriptionStatus::ACTIVE)->count(),
            'trial' => $subscriptions->where('status', SubscriptionStatus::TRIAL)->count(),
            'expired' => $subscriptions->where('status', SubscriptionStatus::EXPIRED)->count(),
            'suspended' => $subscriptions->where('status', SubscriptionStatus::SUSPENDED)->count(),
        ];
    }
}
