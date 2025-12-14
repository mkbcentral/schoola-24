<?php

namespace App\Actions\User;

use App\DTOs\User\UserDTO;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

class CreateUserAction
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    /**
     * Créer un nouvel utilisateur
     */
    public function execute(UserDTO $dto): User
    {
        return DB::transaction(function () use ($dto) {
            $userData = $dto->toCreateArray();

            // Générer un username unique si non fourni
            if (empty($userData['username'])) {
                $userData['username'] = $this->generateUniqueUsername($dto->name);
            }

            return $this->userRepository->create($userData);
        });
    }

    /**
     * Générer un username unique
     */
    private function generateUniqueUsername(string $name): string
    {
        $baseUsername = strtolower(str_replace(' ', '.', $name));
        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }
}
