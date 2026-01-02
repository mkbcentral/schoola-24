<?php

namespace App\Policies;

use App\Enums\RoleType;
use App\Models\Module;
use App\Models\User;

class ModulePolicy
{
    /**
     * Determine if the user can view any modules.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role->name, [
            RoleType::ROOT,
            RoleType::APP_ADMIN,
        ]);
    }

    /**
     * Determine if the user can view the module.
     */
    public function view(User $user, Module $module): bool
    {
        return in_array($user->role->name, [
            RoleType::ROOT,
            RoleType::APP_ADMIN,
        ]);
    }

    /**
     * Determine if the user can create modules.
     */
    public function create(User $user): bool
    {
        return in_array($user->role->name, [
            RoleType::ROOT,
            RoleType::APP_ADMIN,
        ]);
    }

    /**
     * Determine if the user can update the module.
     */
    public function update(User $user, Module $module): bool
    {
        return in_array($user->role->name, [
            RoleType::ROOT,
            RoleType::APP_ADMIN,
        ]);
    }

    /**
     * Determine if the user can delete the module.
     */
    public function delete(User $user, Module $module): bool
    {
        return $user->role->name === RoleType::ROOT;
    }

    /**
     * Determine if the user can manage module features.
     */
    public function manageFeatures(User $user, Module $module): bool
    {
        return in_array($user->role->name, [
            RoleType::ROOT,
            RoleType::APP_ADMIN,
        ]);
    }

    /**
     * Determine if the user can manage module pricing.
     */
    public function managePricing(User $user, Module $module): bool
    {
        return in_array($user->role->name, [
            RoleType::ROOT,
            RoleType::APP_ADMIN,
        ]);
    }
}
