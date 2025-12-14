<?php

namespace App\Actions\Role;

use App\DTOs\User\RoleDTO;
use App\Repositories\RoleRepository;
use Illuminate\Support\Facades\DB;

class UpdateRoleAction
{
    public function __construct(
        private RoleRepository $roleRepository
    ) {}

    /**
     * Mettre à jour un rôle
     */
    public function execute(int $roleId, RoleDTO $dto): bool
    {
        return DB::transaction(function () use ($roleId, $dto) {
            // Vérifier que le rôle existe
            $role = $this->roleRepository->findById($roleId);

            if (!$role) {
                throw new \Exception('Rôle introuvable.');
            }

            // Vérifier l'unicité du nom si modifié
            if ($dto->name && $dto->name !== $role->name) {
                $existingRole = $this->roleRepository->findByName($dto->name);

                if ($existingRole) {
                    throw new \Exception('Un rôle avec ce nom existe déjà.');
                }
            }

            return $this->roleRepository->update($roleId, $dto->toUpdateArray());
        });
    }
}
