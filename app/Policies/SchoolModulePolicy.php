<?php

namespace App\Policies;

use App\Enums\RoleType;
use App\Models\SchoolModule;
use App\Models\User;

class SchoolModulePolicy
{
    /**
     * Determine if the user can view any subscriptions.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role->name, [
            RoleType::ROOT,
            RoleType::APP_ADMIN,
            RoleType::ADMIN_SCHOOL,
            RoleType::SCHOOL_BOSS,
            RoleType::SCHOOL_MANAGER,
        ]);
    }

    /**
     * Determine if the user can view the subscription.
     */
    public function view(User $user, SchoolModule $schoolModule): bool
    {
        // Admin global peut tout voir
        if (in_array($user->role->name, [RoleType::ROOT, RoleType::APP_ADMIN])) {
            return true;
        }

        // Utilisateur de l'école peut voir les souscriptions de son école
        return $user->school_id === $schoolModule->school_id
            && in_array($user->role->name, [
                RoleType::ADMIN_SCHOOL,
                RoleType::SCHOOL_BOSS,
                RoleType::SCHOOL_MANAGER,
            ]);
    }

    /**
     * Determine if the user can subscribe to a module.
     */
    public function subscribe(User $user): bool
    {
        return in_array($user->role->name, [
            RoleType::ROOT,
            RoleType::APP_ADMIN,
            RoleType::ADMIN_SCHOOL,
            RoleType::SCHOOL_BOSS,
        ]);
    }

    /**
     * Determine if the user can renew a subscription.
     */
    public function renew(User $user, SchoolModule $schoolModule): bool
    {
        // Admin global peut tout renouveler
        if (in_array($user->role->name, [RoleType::ROOT, RoleType::APP_ADMIN])) {
            return true;
        }

        // Utilisateur de l'école peut renouveler les souscriptions de son école
        return $user->school_id === $schoolModule->school_id
            && in_array($user->role->name, [
                RoleType::ADMIN_SCHOOL,
                RoleType::SCHOOL_BOSS,
            ]);
    }

    /**
     * Determine if the user can unsubscribe from a module.
     */
    public function unsubscribe(User $user, SchoolModule $schoolModule): bool
    {
        // Admin global peut tout désabonner
        if (in_array($user->role->name, [RoleType::ROOT, RoleType::APP_ADMIN])) {
            return true;
        }

        // Utilisateur de l'école peut désabonner son école
        return $user->school_id === $schoolModule->school_id
            && in_array($user->role->name, [
                RoleType::ADMIN_SCHOOL,
                RoleType::SCHOOL_BOSS,
            ]);
    }

    /**
     * Determine if the user can suspend a subscription.
     */
    public function suspend(User $user, SchoolModule $schoolModule): bool
    {
        return in_array($user->role->name, [
            RoleType::ROOT,
            RoleType::APP_ADMIN,
        ]);
    }

    /**
     * Determine if the user can activate a subscription.
     */
    public function activate(User $user, SchoolModule $schoolModule): bool
    {
        return in_array($user->role->name, [
            RoleType::ROOT,
            RoleType::APP_ADMIN,
        ]);
    }

    /**
     * Determine if the user can manage subscription settings.
     */
    public function manageSettings(User $user, SchoolModule $schoolModule): bool
    {
        // Admin global peut tout gérer
        if (in_array($user->role->name, [RoleType::ROOT, RoleType::APP_ADMIN])) {
            return true;
        }

        // Utilisateur de l'école peut gérer les paramètres de son école
        return $user->school_id === $schoolModule->school_id
            && in_array($user->role->name, [
                RoleType::ADMIN_SCHOOL,
                RoleType::SCHOOL_BOSS,
            ]);
    }

    /**
     * Determine if the user can view subscription history.
     */
    public function viewHistory(User $user, SchoolModule $schoolModule): bool
    {
        // Admin global peut tout voir
        if (in_array($user->role->name, [RoleType::ROOT, RoleType::APP_ADMIN])) {
            return true;
        }

        // Utilisateur de l'école peut voir l'historique de son école
        return $user->school_id === $schoolModule->school_id
            && in_array($user->role->name, [
                RoleType::ADMIN_SCHOOL,
                RoleType::SCHOOL_BOSS,
                RoleType::SCHOOL_MANAGER,
            ]);
    }

    /**
     * Determine if the user can toggle auto-renewal.
     */
    public function toggleAutoRenew(User $user, SchoolModule $schoolModule): bool
    {
        // Admin global peut tout modifier
        if (in_array($user->role->name, [RoleType::ROOT, RoleType::APP_ADMIN])) {
            return true;
        }

        // Utilisateur de l'école peut modifier pour son école
        return $user->school_id === $schoolModule->school_id
            && in_array($user->role->name, [
                RoleType::ADMIN_SCHOOL,
                RoleType::SCHOOL_BOSS,
            ]);
    }
}
