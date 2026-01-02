<?php

namespace App\Actions\Auth;

use App\Repositories\AuthRepository;

class TrackLoginAttemptAction
{
    public function __construct(
        private AuthRepository $authRepository
    ) {}

    /**
     * Obtenir les informations sur les tentatives de connexion
     *
     * @return array{isLocked: bool, remainingAttempts: int, lockoutTime: int, maxAttempts: int}
     */
    public function execute(string $identifier): array
    {
        $isLocked = $this->authRepository->isLocked($identifier);
        $remainingAttempts = $this->authRepository->getRemainingAttempts($identifier);
        $lockoutTime = $this->authRepository->getLockoutTime($identifier);
        $maxAttempts = $this->authRepository->getMaxAttempts();

        return [
            'isLocked' => $isLocked,
            'remainingAttempts' => $remainingAttempts,
            'lockoutTime' => $lockoutTime,
            'maxAttempts' => $maxAttempts,
        ];
    }

    /**
     * Réinitialiser les tentatives de connexion
     */
    public function resetAttempts(string $identifier): void
    {
        $this->authRepository->clearLoginAttempts($identifier);
    }

    /**
     * Vérifier si un identifiant est actuellement bloqué
     */
    public function isLocked(string $identifier): bool
    {
        return $this->authRepository->isLocked($identifier);
    }

    /**
     * Obtenir le nombre de tentatives restantes
     */
    public function getRemainingAttempts(string $identifier): int
    {
        return $this->authRepository->getRemainingAttempts($identifier);
    }

    /**
     * Obtenir le temps de blocage restant en secondes
     */
    public function getLockoutTime(string $identifier): int
    {
        return $this->authRepository->getLockoutTime($identifier);
    }
}
