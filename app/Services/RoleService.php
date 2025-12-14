<?php

namespace App\Services;

use App\Actions\Role\CreateRoleAction;
use App\Actions\Role\UpdateRoleAction;
use App\Actions\Role\DeleteRoleAction;
use App\DTOs\User\RoleDTO;
use App\Models\Role;
use App\Repositories\RoleRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class RoleService
{
    /**
     * Durée du cache en minutes
     */
    private const CACHE_TTL = 60;

    /**
     * Tags de cache
     */
    private const CACHE_TAGS = ['roles'];

    public function __construct(
        private RoleRepository $roleRepository,
        private CreateRoleAction $createRoleAction,
        private UpdateRoleAction $updateRoleAction,
        private DeleteRoleAction $deleteRoleAction
    ) {}

    /**
     * Récupérer tous les rôles
     */
    public function getAll(): Collection
    {
        $cacheKey = 'roles_all';

        return $this->cacheRemember(
            $cacheKey,
            now()->addMinutes(self::CACHE_TTL),
            fn() => $this->roleRepository->getAll()
        );
    }

    /**
     * Récupérer tous les rôles avec pagination
     */
    public function getAllPaginated(int $perPage = 15, bool $forSchool = true): LengthAwarePaginator
    {
        return $this->roleRepository->getAllPaginated($perPage, $forSchool);
    }

    /**
     * Récupérer tous les rôles avec filtres
     */
    public function getAllWithFilters(?string $search = null, ?bool $isForSchool = null, int $perPage = 15): LengthAwarePaginator
    {
        return $this->roleRepository->getAllWithFilters($search, $isForSchool, $perPage);
    }

    /**
     * Récupérer les rôles pour une école
     */
    public function getForSchool(): Collection
    {
        $cacheKey = 'roles_for_school';

        return $this->cacheRemember(
            $cacheKey,
            now()->addMinutes(self::CACHE_TTL),
            fn() => $this->roleRepository->getForSchool()
        );
    }

    /**
     * Récupérer un rôle par ID
     */
    public function findById(int $id): ?Role
    {
        return $this->roleRepository->findById($id);
    }

    /**
     * Créer un rôle
     */
    public function create(RoleDTO $dto): Role
    {
        $role = $this->createRoleAction->execute($dto);

        $this->clearCache();

        return $role;
    }

    /**
     * Mettre à jour un rôle
     */
    public function update(int $id, RoleDTO $dto): bool
    {
        $result = $this->updateRoleAction->execute($id, $dto);

        if ($result) {
            $this->clearCache();
        }

        return $result;
    }

    /**
     * Supprimer un rôle
     */
    public function delete(int $id): bool
    {
        $result = $this->deleteRoleAction->execute($id);

        if ($result) {
            $this->clearCache();
        }

        return $result;
    }

    /**
     * Récupérer les statistiques des rôles
     */
    public function getStatistics(): array
    {
        $cacheKey = 'roles_statistics';

        return $this->cacheRemember(
            $cacheKey,
            now()->addMinutes(self::CACHE_TTL),
            fn() => $this->roleRepository->getStatistics()
        );
    }

    /**
     * Vérifier si un rôle est utilisé
     */
    public function isUsed(int $id): bool
    {
        return $this->roleRepository->isUsed($id);
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
            Cache::forget('roles_all');
            Cache::forget('roles_for_school');
            Cache::forget('roles_statistics');
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
