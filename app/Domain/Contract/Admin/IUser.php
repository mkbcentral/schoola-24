<?php

namespace App\Domain\Contract\Admin;

interface IUser
{
    /**
     * Summary of getListUser
     */
    public static function getListSchoolUser(
        string $q,
        string $sortBy,
        bool $sortAsc,
        int $per_page = 20
    ): mixed;

    /**
     * Summary of getListUser
     */
    public static function getListAppUser(
        string $q,
        string $sortBy,
        bool $sortAsc,
        int $per_page = 20
    ): mixed;
}
