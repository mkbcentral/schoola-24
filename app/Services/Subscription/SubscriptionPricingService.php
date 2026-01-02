<?php

namespace App\Services\Subscription;

use App\Models\Module;
use App\Models\SchoolModule;
use App\Models\SubscriptionPlan;

class SubscriptionPricingService
{
    /**
     * Calculer le prix d'une souscription
     */
    public function calculatePrice(
        Module $module,
        SubscriptionPlan $plan,
        ?float $discountPercentage = null
    ): float {
        // Prix de base du module pour ce plan
        $basePrice = $this->getModulePriceForPlan($module, $plan);

        // Appliquer la réduction du plan
        $priceAfterPlanDiscount = $basePrice - ($basePrice * ($plan->discount_percentage / 100));

        // Appliquer une réduction supplémentaire si fournie
        if ($discountPercentage !== null) {
            $priceAfterPlanDiscount -= ($priceAfterPlanDiscount * ($discountPercentage / 100));
        }

        return max(0, $priceAfterPlanDiscount);
    }

    /**
     * Obtenir le prix d'un module pour un plan spécifique
     */
    protected function getModulePriceForPlan(Module $module, SubscriptionPlan $plan): float
    {
        // Vérifier s'il y a un prix personnalisé dans la table pivot
        $pivot = $module->plans()->where('plan_id', $plan->id)->first();

        if ($pivot && $pivot->pivot->custom_price !== null) {
            return (float) $pivot->pivot->custom_price;
        }

        // Sinon, utiliser le prix de base du module multiplié par le nombre de mois
        return $module->base_price * $plan->getPeriodMonths();
    }

    /**
     * Calculer le prix au prorata pour un renouvellement
     */
    public function calculateProrata(SchoolModule $subscription, SubscriptionPlan $newPlan): float
    {
        if (!$subscription->expires_at || $subscription->expires_at->isPast()) {
            return $this->calculatePrice($subscription->module, $newPlan);
        }

        // Calculer le nombre de jours restants
        $daysRemaining = now()->diffInDays($subscription->expires_at, false);

        if ($daysRemaining <= 0) {
            return $this->calculatePrice($subscription->module, $newPlan);
        }

        // Calculer le prix total du nouveau plan
        $newPlanPrice = $this->calculatePrice($subscription->module, $newPlan);

        // Calculer le crédit des jours restants de l'ancien plan
        $oldPlanDailyRate = 0;
        if ($subscription->plan && $subscription->paid_amount) {
            $oldPlanTotalDays = $subscription->plan->getPeriodMonths() * 30;
            $oldPlanDailyRate = $subscription->paid_amount / $oldPlanTotalDays;
        }

        $credit = $oldPlanDailyRate * $daysRemaining;

        // Prix final = nouveau prix - crédit
        return max(0, $newPlanPrice - $credit);
    }

    /**
     * Appliquer une réduction
     */
    public function applyDiscount(float $basePrice, float $discountPercentage): float
    {
        $discount = $basePrice * ($discountPercentage / 100);
        return max(0, $basePrice - $discount);
    }

    /**
     * Calculer le coût total pour plusieurs modules
     */
    public function calculateBulkPrice(array $modules, SubscriptionPlan $plan): float
    {
        $total = 0;

        foreach ($modules as $module) {
            if ($module instanceof Module) {
                $total += $this->calculatePrice($module, $plan);
            }
        }

        // Appliquer une réduction pour achat groupé (par exemple 5% pour 3+ modules)
        if (count($modules) >= 3) {
            $total = $this->applyDiscount($total, 5);
        } elseif (count($modules) >= 5) {
            $total = $this->applyDiscount($total, 10);
        }

        return $total;
    }

    /**
     * Obtenir les économies réalisées avec un plan
     */
    public function getSavings(Module $module, SubscriptionPlan $plan): array
    {
        $monthlyPrice = $module->base_price;
        $planPrice = $this->calculatePrice($module, $plan);
        $planMonths = $plan->getPeriodMonths();

        $regularPrice = $monthlyPrice * $planMonths;
        $savings = $regularPrice - $planPrice;
        $savingsPercentage = $regularPrice > 0 ? ($savings / $regularPrice) * 100 : 0;

        return [
            'regular_price' => $regularPrice,
            'plan_price' => $planPrice,
            'savings_amount' => max(0, $savings),
            'savings_percentage' => round($savingsPercentage, 2),
        ];
    }

    /**
     * Recommander le meilleur plan pour une école
     */
    public function recommendPlan(Module $module, ?int $expectedMonths = null): ?SubscriptionPlan
    {
        $plans = $module->plans()->active()->get();

        if ($plans->isEmpty()) {
            return null;
        }

        // Si une durée est spécifiée, recommander le plan correspondant
        if ($expectedMonths) {
            $bestPlan = $plans->sortBy(function ($plan) use ($expectedMonths) {
                return abs($plan->getPeriodMonths() - $expectedMonths);
            })->first();

            return $bestPlan;
        }

        // Sinon, recommander le plan avec le meilleur rapport qualité-prix
        $bestPlan = $plans->sortBy(function ($plan) use ($module) {
            $monthlyPrice = $this->calculatePrice($module, $plan) / $plan->getPeriodMonths();
            return $monthlyPrice;
        })->first();

        return $bestPlan;
    }

    /**
     * Formater un prix avec la devise
     */
    public function formatPrice(float $price, string $currency = 'FC'): string
    {
        return number_format($price, 0, ',', ' ') . ' ' . $currency;
    }
}
