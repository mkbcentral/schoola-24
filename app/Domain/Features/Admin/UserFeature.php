<?php

namespace App\Domain\Features\Admin;

use App\Domain\Contract\Admin\IUser;
use App\Models\School;
use App\Models\User;

class UserFeature implements IUser
{
    /**
     * {@inheritDoc}
     */
    public static function getListSchoolUser(
        string $q,
        string $sortBy,
        bool $sortAsc,
        int $per_page = 20
    ): mixed {
        return User::query()
            ->filter($q)
            ->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->orderBy($sortBy, $sortAsc ? 'ASC' : 'DESC')
            ->paginate($per_page);
    }

    /**
     * {@inheritDoc}
     */
    /**
     * {@inheritDoc}
     */
    public static function getListAppUser(
        string $q,
        string $sortBy,
        bool $sortAsc,
        int $per_page = 20
    ): mixed {
        return User::query()
            ->filter($q)
            ->where('roles.is_for_school', false)
            ->orderBy($sortBy, $sortAsc ? 'ASC' : 'DESC')
            ->paginate($per_page);
    }
}
