<?php

namespace App\Services;

use App\Actions\Auth\AttemptLoginAction;
use App\Actions\Auth\TrackLoginAttemptAction;
use App\DTOs\Auth\LoginDTO;
use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthenticationService
{
    public function __construct(
        private AuthRepository $authRepository,
        private AttemptLoginAction $attemptLoginAction,
        private TrackLoginAttemptAction $trackLoginAttemptAction
    ) {}

    /**
     * Authentifier un utilisateur
     *
     * @return array{success: bool, user: ?User, message: string, remainingAttempts: ?int, lockoutTime: ?int}
     */
    public function login(LoginDTO $dto): array
    {
        // Valider le DTO
        if (!$dto->isValid()) {
            return [
                'success' => false,
                'user' => null,
                'message' => 'Veuillez renseigner tous les champs requis.',
                'remainingAttempts' => null,
                'lockoutTime' => null,
            ];
        }

        // Tenter la connexion
        $result = $this->attemptLoginAction->execute($dto);

        return $result;
    }

    /**
     * Déconnecter l'utilisateur actuel
     */
    public function logout(): void
    {
        $user = Auth::user();

        if ($user) {
            Log::info("Déconnexion de l'utilisateur", [
                'user_id' => $user->id,
                'username' => $user->username,
            ]);
        }

        Auth::logout();

        // Invalider la session
        request()->session()->invalidate();

        // Régénérer le token CSRF
        request()->session()->regenerateToken();
    }

    /**
     * Obtenir les informations sur les tentatives de connexion
     *
     * @return array{isLocked: bool, remainingAttempts: int, lockoutTime: int, maxAttempts: int}
     */
    public function getLoginAttemptInfo(string $identifier): array
    {
        return $this->trackLoginAttemptAction->execute($identifier);
    }

    /**
     * Vérifier si un utilisateur est bloqué
     */
    public function isLocked(string $identifier): bool
    {
        return $this->trackLoginAttemptAction->isLocked($identifier);
    }

    /**
     * Obtenir le nombre de tentatives restantes
     */
    public function getRemainingAttempts(string $identifier): int
    {
        return $this->trackLoginAttemptAction->getRemainingAttempts($identifier);
    }

    /**
     * Obtenir le temps de blocage restant
     */
    public function getLockoutTime(string $identifier): int
    {
        return $this->trackLoginAttemptAction->getLockoutTime($identifier);
    }

    /**
     * Réinitialiser les tentatives de connexion (admin seulement)
     */
    public function resetLoginAttempts(string $identifier): void
    {
        $this->trackLoginAttemptAction->resetAttempts($identifier);

        Log::info("Tentatives de connexion réinitialisées par un administrateur", [
            'identifier' => $identifier,
            'admin_user_id' => Auth::id(),
        ]);
    }

    /**
     * Vérifier si l'utilisateur actuel est authentifié
     */
    public function isAuthenticated(): bool
    {
        return Auth::check();
    }

    /**
     * Obtenir l'utilisateur actuellement connecté
     */
    public function getCurrentUser(): ?User
    {
        return Auth::user();
    }

    /**
     * Vérifier les credentials sans se connecter
     */
    public function validateCredentials(string $identifier, string $password): bool
    {
        $user = $this->authRepository->findByIdentifier($identifier);

        if (!$user) {
            return false;
        }

        return Auth::getProvider()->validateCredentials($user, ['password' => $password]);
    }
}
