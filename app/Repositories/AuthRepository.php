<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AuthRepository
{
    /**
     * Clé de cache pour les tentatives de connexion
     */
    private const LOGIN_ATTEMPTS_KEY = 'login_attempts';

    /**
     * Nombre maximum de tentatives autorisées
     */
    private const MAX_ATTEMPTS = 3;

    /**
     * Durée du blocage en secondes (5 minutes)
     */
    private const LOCKOUT_TIME = 300;

    /**
     * Durée de conservation des tentatives en secondes (15 minutes)
     */
    private const ATTEMPTS_TTL = 900;

    public function __construct(private User $model) {}

    /**
     * Trouver un utilisateur par identifiant (username ou email)
     */
    public function findByIdentifier(string $identifier): ?User
    {
        $user = $this->model->newQuery()
            ->where(function ($query) use ($identifier) {
                $query->where('username', $identifier)
                    ->orWhere('email', $identifier);
            })
            ->first();

        return $user;
    }

    /**
     * Enregistrer une tentative de connexion échouée
     */
    public function recordFailedAttempt(string $identifier, ?string $ipAddress = null): void
    {
        $key = $this->getAttemptsCacheKey($identifier);
        $attempts = $this->getLoginAttempts($identifier);

        $attempts[] = [
            'timestamp' => now()->timestamp,
            'ip_address' => $ipAddress,
        ];

        Cache::put($key, $attempts, self::ATTEMPTS_TTL);

        Log::info("Tentative de connexion échouée", [
            'identifier' => $identifier,
            'ip_address' => $ipAddress,
            'attempts' => count($attempts),
        ]);
    }

    /**
     * Réinitialiser les tentatives de connexion
     */
    public function clearLoginAttempts(string $identifier): void
    {
        $key = $this->getAttemptsCacheKey($identifier);
        Cache::forget($key);

        Log::info("Tentatives de connexion réinitialisées", [
            'identifier' => $identifier,
        ]);
    }

    /**
     * Obtenir le nombre de tentatives de connexion
     */
    public function getLoginAttempts(string $identifier): array
    {
        $key = $this->getAttemptsCacheKey($identifier);
        return Cache::get($key, []);
    }

    /**
     * Obtenir le nombre de tentatives restantes
     */
    public function getRemainingAttempts(string $identifier): int
    {
        $attempts = count($this->getLoginAttempts($identifier));
        return max(0, self::MAX_ATTEMPTS - $attempts);
    }

    /**
     * Vérifier si l'utilisateur est bloqué
     */
    public function isLocked(string $identifier): bool
    {
        $attempts = $this->getLoginAttempts($identifier);

        if (count($attempts) < self::MAX_ATTEMPTS) {
            return false;
        }

        // Vérifier si le dernier blocage est toujours actif
        $lastAttempt = end($attempts);
        $timeSinceLastAttempt = now()->timestamp - $lastAttempt['timestamp'];

        if ($timeSinceLastAttempt >= self::LOCKOUT_TIME) {
            // Le blocage a expiré, réinitialiser les tentatives
            $this->clearLoginAttempts($identifier);
            return false;
        }

        return true;
    }

    /**
     * Obtenir le temps restant avant de pouvoir réessayer (en secondes)
     */
    public function getLockoutTime(string $identifier): int
    {
        $attempts = $this->getLoginAttempts($identifier);

        if (count($attempts) < self::MAX_ATTEMPTS) {
            return 0;
        }

        $lastAttempt = end($attempts);
        $timeSinceLastAttempt = now()->timestamp - $lastAttempt['timestamp'];

        return max(0, self::LOCKOUT_TIME - $timeSinceLastAttempt);
    }

    /**
     * Vérifier si l'utilisateur est actif
     */
    public function isUserActive(User $user): bool
    {
        return (bool) $user->is_active;
    }

    /**
     * Enregistrer la dernière connexion réussie
     */
    public function recordSuccessfulLogin(User $user, ?string $ipAddress = null): void
    {
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $ipAddress,
        ]);

        Log::info("Connexion réussie", [
            'user_id' => $user->id,
            'username' => $user->username,
            'ip_address' => $ipAddress,
        ]);
    }

    /**
     * Générer la clé de cache pour les tentatives
     */
    private function getAttemptsCacheKey(string $identifier): string
    {
        return self::LOGIN_ATTEMPTS_KEY . ':' . md5($identifier);
    }

    /**
     * Obtenir le nombre maximum de tentatives autorisées
     */
    public function getMaxAttempts(): int
    {
        return self::MAX_ATTEMPTS;
    }

    /**
     * Obtenir la durée du blocage en secondes
     */
    public function getLockoutDuration(): int
    {
        return self::LOCKOUT_TIME;
    }
}
