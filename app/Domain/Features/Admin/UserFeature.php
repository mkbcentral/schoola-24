<?php

namespace App\Domain\Features\Admin;

use App\Domain\Contract\Admin\IUser;
use App\Models\School;
use App\Models\User;

class UserFeature implements IUser
{
    private static string $keyToSearch = '';
    /**
     * @inheritDoc
     */
    public static function getListSchoolUser(
        string $q,
        string $sortBy,
        bool $sortAsc,
        int $per_page = 20
    ): mixed {
        SELF::$keyToSearch = $q;
        return  User::query()
            ->when($q, function ($query) {
                return $query->where(function ($query) {
                    return $query->where('name', 'like', '%' . SELF::$keyToSearch . '%')
                        ->orWhere('phone', 'like', '%' . SELF::$keyToSearch . '%')
                        ->orWhere('email', 'like', '%' . SELF::$keyToSearch . '%');
                });
            })
            ->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->with(['role'])
            ->orderBy($sortBy, $sortAsc ? 'ASC' : 'DESC')
            ->paginate($per_page);
    }
    /**
     * @inheritDoc
     */
    /**
     * @inheritDoc
     */
    public static function getListAppUser(
        string $q,
        string $sortBy,
        bool $sortAsc,
        int $per_page = 20
    ): mixed {
        SELF::$keyToSearch = $q;
        return  User::query()
            ->join('roles', 'roles.id', 'users.role_id')
            ->when($q, function ($query) {
                return $query->where(function ($query) {
                    return $query->where('name', 'like', '%' . SELF::$keyToSearch . '%')
                        ->orWhere('phone', 'like', '%' . SELF::$keyToSearch . '%')
                        ->orWhere('email', 'like', '%' . SELF::$keyToSearch . '%');
                });
            })
            ->where('roles.is_for_school', false)
            ->select('users.*')
            ->with(['role'])
            ->orderBy($sortBy, $sortAsc ? 'ASC' : 'DESC')
            ->paginate($per_page);
    }
}
