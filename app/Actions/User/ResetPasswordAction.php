<?php

namespace App\Actions\User;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordAction
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    /**
     * Réinitialiser le mot de passe d'un utilisateur
     */
    public function execute(int $userId, string $newPassword): bool
    {
        return DB::transaction(function () use ($userId, $newPassword) {
            // Valider la force du mot de passe
            if (strlen($newPassword) < 8) {
                throw new \Exception('Le mot de passe doit contenir au moins 8 caractères.');
            }

            return $this->userRepository->resetPassword($userId, $newPassword);
        });
    }

    /**
     * Générer un mot de passe aléatoire
     */
    public function generateRandomPassword(int $length = 12): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $password;
    }
}
