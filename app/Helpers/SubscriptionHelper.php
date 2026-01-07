<?php

namespace App\Helpers;

class SubscriptionHelper
{
    /**
     * Vérifier si un abonnement est valide
     *
     * @param mixed $subscription
     * @return bool
     */
    public static function isValid($subscription): bool
    {
        if (!$subscription) {
            return false;
        }

        // Logique de validation d'abonnement
        return true;
    }

    /**
     * Obtenir les jours restants d'un abonnement
     *
     * @param mixed $subscription
     * @return int
     */
    public static function getDaysRemaining($subscription): int
    {
        if (!$subscription) {
            return 0;
        }

        // Logique de calcul des jours restants
        return 30;
    }
}
