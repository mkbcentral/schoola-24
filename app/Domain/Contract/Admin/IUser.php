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
    public static function getListUser(
        string $q,
        string $sortBy,
        bool $sortAsc,
        int $per_page = 20
    ): mixed;
}
