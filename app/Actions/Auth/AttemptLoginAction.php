<?php

namespace App\Actions\Auth;

use App\DTOs\Auth\LoginDTO;
use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AttemptLoginAction
{
    public function __construct(
        private AuthRepository $authRepository
    ) {}

    /**
     * Tenter une connexion
     *
     * @return array{success: bool, user: ?User, message: string, remainingAttempts: ?int, lockoutTime: ?int}
     */
    public function execute(LoginDTO $dto): array
    {
        // Vérifier si l'utilisateur est bloqué
        if ($this->authRepository->isLocked($dto->identifier)) {
            $lockoutTime = $this->authRepository->getLockoutTime($dto->identifier);

            Log::warning("Tentative de connexion bloquée", [
                'identifier' => $dto->identifier,
                'ip_address' => $dto->ipAddress,
                'lockout_time' => $lockoutTime,
            ]);

            return [
                'success' => false,
                'user' => null,
                'message' => 'Trop de tentatives échouées. Veuillez réessayer dans ' . $this->formatLockoutTime($lockoutTime) . '.',
                'remainingAttempts' => 0,
                'lockoutTime' => $lockoutTime,
            ];
        }

        // Chercher l'utilisateur
        $user = $this->authRepository->findByIdentifier($dto->identifier);

        // Vérifier si l'utilisateur existe et le mot de passe est correct
        if (!$user || !Hash::check($dto->password, $user->password)) {
            // Enregistrer la tentative échouée
            $this->authRepository->recordFailedAttempt($dto->identifier, $dto->ipAddress);
            $remainingAttempts = $this->authRepository->getRemainingAttempts($dto->identifier);

            Log::warning("Identifiants invalides", [
                'identifier' => $dto->identifier,
                'ip_address' => $dto->ipAddress,
                'remaining_attempts' => $remainingAttempts,
            ]);

            $message = 'Identifiants incorrects.';
            if ($remainingAttempts > 0) {
                $message .= ' Il vous reste ' . $remainingAttempts . ' tentative(s).';
            } else {
                $lockoutTime = $this->authRepository->getLockoutTime($dto->identifier);
                $message = 'Trop de tentatives échouées. Veuillez réessayer dans ' . $this->formatLockoutTime($lockoutTime) . '.';
            }

            return [
                'success' => false,
                'user' => null,
                'message' => $message,
                'remainingAttempts' => $remainingAttempts,
                'lockoutTime' => $remainingAttempts === 0 ? $this->authRepository->getLockoutTime($dto->identifier) : null,
            ];
        }

        // Vérifier si l'utilisateur est actif
        if (!$this->authRepository->isUserActive($user)) {
            Log::warning("Tentative de connexion avec compte inactif", [
                'user_id' => $user->id,
                'username' => $user->username,
                'ip_address' => $dto->ipAddress,
            ]);

            return [
                'success' => false,
                'user' => null,
                'message' => 'Votre compte est désactivé. Veuillez contacter l\'administrateur.',
                'remainingAttempts' => null,
                'lockoutTime' => null,
            ];
        }

        // Connexion réussie
        Auth::login($user, $dto->remember);

        // Enregistrer la connexion réussie
        $this->authRepository->recordSuccessfulLogin($user, $dto->ipAddress);

        // Réinitialiser les tentatives
        $this->authRepository->clearLoginAttempts($dto->identifier);

        Log::info("Connexion réussie", [
            'user_id' => $user->id,
            'username' => $user->username,
            'ip_address' => $dto->ipAddress,
        ]);

        return [
            'success' => true,
            'user' => $user,
            'message' => 'Connexion réussie.',
            'remainingAttempts' => null,
            'lockoutTime' => null,
        ];
    }

    /**
     * Formater le temps de blocage pour l'affichage
     */
    private function formatLockoutTime(int $seconds): string
    {
        $minutes = ceil($seconds / 60);

        if ($minutes <= 1) {
            return $seconds . ' seconde(s)';
        }

        return $minutes . ' minute(s)';
    }
}
