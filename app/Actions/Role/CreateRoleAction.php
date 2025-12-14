<?php

namespace App\Actions\Role;

use App\DTOs\User\RoleDTO;
use App\Models\Role;
use App\Repositories\RoleRepository;
use Illuminate\Support\Facades\DB;

class CreateRoleAction
{
    public function __construct(
        private RoleRepository $roleRepository
    ) {}

    /**
     * Créer un nouveau rôle
     */
    public function execute(RoleDTO $dto): Role
    {
        return DB::transaction(function () use ($dto) {
            // Vérifier l'unicité du nom
            $existingRole = $this->roleRepository->findByName($dto->name);

            if ($existingRole) {
                throw new \Exception('Un rôle avec ce nom existe déjà.');
            }

            return $this->roleRepository->create($dto->toCreateArray());
        });
    }
}
