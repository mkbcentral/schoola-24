<?php

namespace App\Policies;

use App\Models\User;
use App\Models\School;
use App\Enums\RoleType;

class SchoolPolicy
{
    /**
     * Déterminer si l'utilisateur peut voir la liste des écoles
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role->name, [
            RoleType::APP_ADMIN,
            RoleType::ROOT,
        ]);
    }

    /**
     * Déterminer si l'utilisateur peut voir une école spécifique
     */
    public function view(User $user, School $school): bool
    {
        return in_array($user->role->name, [
            RoleType::APP_ADMIN,
            RoleType::ROOT,
        ]);
    }

    /**
     * Déterminer si l'utilisateur peut créer une école
     */
    public function create(User $user): bool
    {
        return in_array($user->role->name, [
            RoleType::APP_ADMIN,
            RoleType::ROOT,
        ]);
    }

    /**
     * Déterminer si l'utilisateur peut modifier une école
     */
    public function update(User $user, School $school): bool
    {
        return in_array($user->role->name, [
            RoleType::APP_ADMIN,
            RoleType::ROOT,
        ]);
    }

    /**
     * Déterminer si l'utilisateur peut supprimer une école
     */
    public function delete(User $user, School $school): bool
    {
        // Seul ROOT peut supprimer une école
        return $user->role->name === RoleType::ROOT;
    }

    /**
     * Déterminer si l'utilisateur peut gérer les utilisateurs d'une école
     */
    public function manageUsers(User $user, School $school): bool
    {
        return in_array($user->role->name, [
            RoleType::APP_ADMIN,
            RoleType::ROOT,
        ]);
    }

    /**
     * Déterminer si l'utilisateur peut créer un utilisateur pour une école
     */
    public function createUser(User $user, School $school): bool
    {
        return in_array($user->role->name, [
            RoleType::APP_ADMIN,
            RoleType::ROOT,
        ]);
    }

    /**
     * Déterminer si l'utilisateur peut modifier un utilisateur d'une école
     */
    public function updateUser(User $user, User $targetUser): bool
    {
        // Ne peut pas modifier un utilisateur ROOT
        if ($targetUser->role->name === RoleType::ROOT) {
            return $user->role->name === RoleType::ROOT;
        }

        return in_array($user->role->name, [
            RoleType::APP_ADMIN,
            RoleType::ROOT,
        ]);
    }

    /**
     * Déterminer si l'utilisateur peut supprimer un utilisateur d'une école
     */
    public function deleteUser(User $user, User $targetUser): bool
    {
        // Ne peut pas supprimer un utilisateur ROOT
        if ($targetUser->role->name === RoleType::ROOT) {
            return false;
        }

        // Ne peut pas se supprimer soi-même
        if ($user->id === $targetUser->id) {
            return false;
        }

        return in_array($user->role->name, [
            RoleType::APP_ADMIN,
            RoleType::ROOT,
        ]);
    }

    /**
     * Déterminer si l'utilisateur peut activer/désactiver une école
     */
    public function toggleStatus(User $user, School $school): bool
    {
        return in_array($user->role->name, [
            RoleType::APP_ADMIN,
            RoleType::ROOT,
        ]);
    }
}
