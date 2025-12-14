<?php

namespace App\Actions\User;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

class DeleteUserAction
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    /**
     * Supprimer un utilisateur
     */
    public function execute(int $userId): bool
    {
        return DB::transaction(function () use ($userId) {
            // Vérifier que l'utilisateur existe
            $user = $this->userRepository->findById($userId);

            if (!$user) {
                throw new \Exception('Utilisateur introuvable.');
            }

            // Empêcher la suppression si l'utilisateur a des données associées critiques
            // Par exemple, des paiements
            if ($user->payments()->count() > 0) {
                throw new \Exception('Impossible de supprimer cet utilisateur car il a des paiements associés.');
            }

            return $this->userRepository->delete($userId);
        });
    }
}
