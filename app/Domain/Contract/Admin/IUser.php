<?php

namespace App\Domain\Contract\Admin;

interface IUser
{
    /**
     * Summary of getListUser
     * @param string $q
     * @param string $sortBy
     * @param bool $sortAsc
     * @param int $per_page
     * @return mixed
     */
    public static function getListSchoolUser(
        string $q,
        string $sortBy,
        bool $sortAsc,
        int $per_page = 20
    ): mixed;

    /**
     * Summary of getListUser
     * @param string $q
     * @param string $sortBy
     * @param bool $sortAsc
     * @param int $per_page
     * @return mixed
     */
    public static function getListAppUser(
        string $q,
        string $sortBy,
        bool $sortAsc,
        int $per_page = 20
    ): mixed;
}
