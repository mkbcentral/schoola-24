<?php

namespace App\Services;

use App\Actions\User\CreateUserAction;
use App\Actions\User\UpdateUserAction;
use App\Actions\User\ResetPasswordAction;
use App\Actions\User\DeleteUserAction;
use App\DTOs\User\UserDTO;
use App\DTOs\User\UserFilterDTO;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class UserService
{
    /**
     * Durée du cache en minutes
     */
    private const CACHE_TTL = 60;

    /**
     * Tags de cache
     */
    private const CACHE_TAGS = ['users'];

    public function __construct(
        private UserRepository $userRepository,
        private CreateUserAction $createUserAction,
        private UpdateUserAction $updateUserAction,
        private ResetPasswordAction $resetPasswordAction,
        private DeleteUserAction $deleteUserAction
    ) {}

    /**
     * Récupérer tous les utilisateurs avec filtres
     */
    public function getAllWithFilters(UserFilterDTO $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->userRepository->getAllWithFilters($filters, $perPage);
    }

    /**
     * Récupérer un utilisateur par ID
     */
    public function findById(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    /**
     * Créer un utilisateur
     */
    public function create(UserDTO $dto): User
    {
        $user = $this->createUserAction->execute($dto);

        $this->clearCache();

        return $user;
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(int $id, UserDTO $dto): bool
    {
        $result = $this->updateUserAction->execute($id, $dto);

        if ($result) {
            $this->clearCache();
        }

        return $result;
    }

    /**
     * Supprimer un utilisateur
     */
    public function delete(int $id): bool
    {
        $result = $this->deleteUserAction->execute($id);

        if ($result) {
            $this->clearCache();
        }

        return $result;
    }

    /**
     * Réinitialiser le mot de passe
     */
    public function resetPassword(int $userId, string $newPassword): bool
    {
        return $this->resetPasswordAction->execute($userId, $newPassword);
    }

    /**
     * Générer un mot de passe aléatoire
     */
    public function generateRandomPassword(int $length = 12): string
    {
        return $this->resetPasswordAction->generateRandomPassword($length);
    }

    /**
     * Récupérer les statistiques des utilisateurs
     */
    public function getStatistics(int $schoolId): array
    {
        $cacheKey = "users_statistics_{$schoolId}";

        return $this->cacheRemember(
            $cacheKey,
            now()->addMinutes(self::CACHE_TTL),
            fn() => $this->userRepository->getStatistics($schoolId)
        );
    }

    /**
     * Récupérer les utilisateurs connectés
     */
    public function getOnlineUsers(int $schoolId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->userRepository->getOnlineUsers($schoolId, $perPage);
    }

    /**
     * Récupérer les utilisateurs déconnectés
     */
    public function getOfflineUsers(int $schoolId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->userRepository->getOfflineUsers($schoolId, $perPage);
    }

    /**
     * Mettre à jour le statut en ligne
     */
    public function updateOnlineStatus(int $userId, bool $isOnline): bool
    {
        $result = $this->userRepository->updateOnlineStatus($userId, $isOnline);

        if ($result) {
            $this->clearCache();
        }

        return $result;
    }

    /**
     * Récupérer tous les utilisateurs d'une école
     */
    public function getAllBySchool(int $schoolId): Collection
    {
        $cacheKey = "users_school_{$schoolId}";

        return $this->cacheRemember(
            $cacheKey,
            now()->addMinutes(self::CACHE_TTL),
            fn() => $this->userRepository->getAllBySchool($schoolId)
        );
    }

    /**
     * Compter les utilisateurs par rôle
     */
    public function countByRole(int $schoolId, int $roleId): int
    {
        return $this->userRepository->countByRole($schoolId, $roleId);
    }

    /**
     * Vider le cache
     */
    private function clearCache(): void
    {
        if ($this->supportsCacheTags()) {
            Cache::tags(self::CACHE_TAGS)->flush();
        } else {
            // Fallback: vider les clés spécifiques
            Cache::flush();
        }
    }

    /**
     * Méthode helper pour utiliser le cache avec ou sans tags
     */
    private function cacheRemember(string $key, \DateTimeInterface|\DateInterval|int $ttl, \Closure $callback): mixed
    {
        if ($this->supportsCacheTags()) {
            return Cache::tags(self::CACHE_TAGS)->remember($key, $ttl, $callback);
        }

        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * Vérifier si le driver de cache supporte les tags
     */
    private function supportsCacheTags(): bool
    {
        $driver = config('cache.default');
        return in_array($driver, ['redis', 'memcached']);
    }
}
