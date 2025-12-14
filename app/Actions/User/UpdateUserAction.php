<?php

namespace App\Actions\User;

use App\DTOs\User\UserDTO;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

class UpdateUserAction
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    /**
     * Mettre à jour un utilisateur
     */
    public function execute(int $userId, UserDTO $dto): bool
    {
        return DB::transaction(function () use ($userId, $dto) {
            $userData = $dto->toUpdateArray();

            // Vérifier l'unicité du username si modifié
            if (isset($userData['username'])) {
                $existingUser = User::where('username', $userData['username'])
                    ->where('id', '!=', $userId)
                    ->first();

                if ($existingUser) {
                    throw new \Exception('Ce nom d\'utilisateur est déjà utilisé.');
                }
            }

            // Vérifier l'unicité de l'email si modifié
            if (isset($userData['email'])) {
                $existingUser = User::where('email', $userData['email'])
                    ->where('id', '!=', $userId)
                    ->first();

                if ($existingUser) {
                    throw new \Exception('Cet email est déjà utilisé.');
                }
            }

            return $this->userRepository->update($userId, $userData);
        });
    }
}
