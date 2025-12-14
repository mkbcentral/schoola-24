<?php

namespace App\Actions\Role;

use App\Repositories\RoleRepository;
use Illuminate\Support\Facades\DB;

class DeleteRoleAction
{
    public function __construct(
        private RoleRepository $roleRepository
    ) {}

    /**
     * Supprimer un rôle
     */
    public function execute(int $roleId): bool
    {
        return DB::transaction(function () use ($roleId) {
            // Vérifier que le rôle existe
            $role = $this->roleRepository->findById($roleId);

            if (!$role) {
                throw new \Exception('Rôle introuvable.');
            }

            // Vérifier que le rôle n'est pas utilisé
            if ($this->roleRepository->isUsed($roleId)) {
                throw new \Exception('Impossible de supprimer ce rôle car il est utilisé par des utilisateurs.');
            }

            return $this->roleRepository->delete($roleId);
        });
    }
}
